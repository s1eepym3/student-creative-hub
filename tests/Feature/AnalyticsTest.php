<?php

namespace Tests\Feature;

use App\Events\ViewRecorded;
use App\Models\Category;
use App\Models\Mahasiswa;
use App\Models\Project;
use App\Models\User;
use App\Models\View;
use App\Services\AnalyticsReportService;
use App\Services\ViewTrackingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected User $studentUser;
    protected Mahasiswa $mahasiswa;
    protected Project $project;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create a Category
        $this->category = Category::create([
            'nama_kategori' => 'Web Development',
        ]);

        // 2. Create Student User & Mahasiswa Profile
        $this->studentUser = User::create([
            'name'     => 'Ahmad Dani',
            'email'    => 'ahmad.dani@student.local',
            'password' => bcrypt('password'),
            'role'     => 'mahasiswa',
            'status'   => 'active',
        ]);

        $this->mahasiswa = Mahasiswa::create([
            'user_id'      => $this->studentUser->id,
            'nim'          => '1234567890',
            'slug'         => 'ahmad-dani',
            'nama_lengkap' => 'Ahmad Dani',
            'prodi'        => 'Teknik Informatika',
            'angkatan'     => 2024,
            'bio'          => 'Laravel developer.',
        ]);

        // 3. Create an Approved Project
        $this->project = Project::create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'category_id'  => $this->category->id,
            'judul'        => 'Student Creative Hub Portfolio App',
            'slug'         => 'student-creative-hub-portfolio-app',
            'deskripsi'    => 'Portfolio app built with Laravel and Bootstrap.',
            'thumbnail'    => 'projects/thumbnails/demo.jpg',
            'status'       => 'approved',
            'visibility'   => 'public',
        ]);
    }

    /**
     * Test 1: Event Dispatch and Analytics Tracking for Public Profile.
     */
    public function test_public_profile_view_dispatches_event_and_tracks_views(): void
    {
        Event::fake();

        // Visit public portfolio profile
        $response = $this->get('/p/ahmad-dani');
        $response->assertStatus(200);

        // Verify Event was dispatched
        Event::assertDispatched(ViewRecorded::class, function ($event) {
            return $event->viewable instanceof Mahasiswa && $event->viewable->id === $this->mahasiswa->id;
        });

        // Trigger listener manually to record the view (since Event is faked)
        $trackingService = app(ViewTrackingService::class);
        $trackingService->recordView($this->mahasiswa, '192.168.1.1', 'Mozilla/5.0', 'direct');

        $this->assertDatabaseHas('views', [
            'mahasiswa_id'  => $this->mahasiswa->id,
            'viewable_type' => Mahasiswa::class,
            'viewable_id'   => $this->mahasiswa->id,
            'source'        => 'direct',
        ]);
    }

    /**
     * Test 2: Project Detail View dispatches event and tracks referer.
     */
    public function test_project_detail_view_dispatches_event_and_tracks_views(): void
    {
        Event::fake();

        // Visit project detail page
        $response = $this->get('/project/student-creative-hub-portfolio-app');
        $response->assertStatus(200);

        // Verify Event was dispatched
        Event::assertDispatched(ViewRecorded::class, function ($event) {
            return $event->viewable instanceof Project && $event->viewable->id === $this->project->id;
        });

        // Test external referer mapping
        $trackingService = app(ViewTrackingService::class);
        $trackingService->recordView($this->project, '1.1.1.1', 'Chrome/120', 'external');

        $this->assertDatabaseHas('views', [
            'mahasiswa_id'  => $this->mahasiswa->id,
            'viewable_type' => Project::class,
            'viewable_id'   => $this->project->id,
            'source'        => 'external',
        ]);
    }

    /**
     * Test 3: QR Code URL referrer is correctly mapped.
     */
    public function test_qr_source_tracking(): void
    {
        // Visit profile page with ?ref=qr
        $this->get('/p/ahmad-dani?ref=qr')->assertStatus(200);

        // Verify database entry has source = qr
        $this->assertDatabaseHas('views', [
            'mahasiswa_id'  => $this->mahasiswa->id,
            'viewable_type' => Mahasiswa::class,
            'source'        => 'qr',
        ]);
    }

    /**
     * Test 4: Student viewing their own profile/projects should be ignored.
     */
    public function test_self_views_are_ignored(): void
    {
        // Log in as the student
        $this->actingAs($this->studentUser);

        // View own profile
        $this->get('/p/ahmad-dani')->assertStatus(200);

        // Verify no view is recorded
        $this->assertDatabaseCount('views', 0);

        // View own project
        $this->get('/project/student-creative-hub-portfolio-app')->assertStatus(200);

        // Verify still no view is recorded
        $this->assertDatabaseCount('views', 0);
    }

    /**
     * Test 5: Anti-spam prevents duplicate view counts.
     */
    public function test_antispam_mechanism_prevents_duplicate_views(): void
    {
        $trackingService = app(ViewTrackingService::class);

        // Log first view
        $trackingService->recordView($this->mahasiswa, '192.168.1.1', 'Mozilla/5.0', 'direct');
        $this->assertDatabaseCount('views', 1);

        // Attempt a repeat view in the same session immediately (refresh)
        $trackingService->recordView($this->mahasiswa, '192.168.1.1', 'Mozilla/5.0', 'direct');
        $this->assertDatabaseCount('views', 1); // No new entry

        // Clear session but attempt with same IP/User-Agent within 15 minutes (IP cooldown block)
        Session::flush();
        $trackingService->recordView($this->mahasiswa, '192.168.1.1', 'Mozilla/5.0', 'direct');
        $this->assertDatabaseCount('views', 1); // No new entry (DB cooldown blocks it, then re-sets session key)

        // Simulate 16 minutes later AND clear session (session was re-set by DB cooldown path)
        Carbon::setTestNow(now()->addMinutes(16));
        Session::flush();
        $trackingService->recordView($this->mahasiswa, '192.168.1.1', 'Mozilla/5.0', 'direct');
        $this->assertDatabaseCount('views', 2); // New entry recorded

        Carbon::setTestNow(); // Reset time mock
    }

    /**
     * Test 6: Dashboard Caching and Invalidation.
     */
    public function test_analytics_dashboard_data_is_cached_and_invalidated_on_new_views(): void
    {
        $reportService = app(AnalyticsReportService::class);
        $trackingService = app(ViewTrackingService::class);

        $cacheKey = "analytics_dashboard_{$this->mahasiswa->id}";

        // 1. Initial retrieval caching
        $this->assertFalse(Cache::has($cacheKey));
        $reportService->getDashboardData($this->mahasiswa);
        $this->assertTrue(Cache::has($cacheKey));

        // 2. Clear cache on new view logged
        $trackingService->recordView($this->mahasiswa, '192.168.1.5', 'Mozilla/5.0', 'direct');
        $this->assertFalse(Cache::has($cacheKey));
    }

    /**
     * Test 7: Safe Growth calculation.
     */
    public function test_growth_calculation_safely_handles_zeros(): void
    {
        $reportService = app(AnalyticsReportService::class);

        // Access protected calculateGrowth method via reflection
        $reflection = new \ReflectionClass(AnalyticsReportService::class);
        $method = $reflection->getMethod('calculateGrowth');
        $method->setAccessible(true);

        // Zeros (Previous = 0, Current = 0) -> should be 'N/A' or '0%'
        $result = $method->invokeArgs($reportService, [0, 0]);
        $this->assertEquals('N/A', $result['text']);
        $this->assertEquals('none', $result['direction']);

        // New Views (Previous = 0, Current = 5) -> should be 'New'
        $result = $method->invokeArgs($reportService, [5, 0]);
        $this->assertEquals('New', $result['text']);
        $this->assertEquals('up', $result['direction']);

        // Standard positive growth (Previous = 10, Current = 15) -> +50%
        $result = $method->invokeArgs($reportService, [15, 10]);
        $this->assertEquals('↑ 50%', $result['text']);
        $this->assertEquals('up', $result['direction']);

        // Standard negative growth (Previous = 100, Current = 80) -> -20%
        $result = $method->invokeArgs($reportService, [80, 100]);
        $this->assertEquals('↓ 20%', $result['text']);
        $this->assertEquals('down', $result['direction']);
    }

    /**
     * Test 8: Insight Rules Engine.
     */
    public function test_insight_rules_engine(): void
    {
        $reportService = app(AnalyticsReportService::class);

        // Retrieve insights with current setup (completion is low, projects count < 3, no QR views, no certificates)
        $data = $reportService->getDashboardData($this->mahasiswa);
        $insights = $data['insights'];

        // Should trigger INS-LOW-COMPLETION
        $this->assertTrue(collect($insights)->contains('rule_id', 'INS-LOW-COMPLETION'));

        // Should trigger INS-LOW-PROJECTS
        $this->assertTrue(collect($insights)->contains('rule_id', 'INS-LOW-PROJECTS'));

        // Should trigger INS-NO-CERTIFICATES
        $this->assertTrue(collect($insights)->contains('rule_id', 'INS-NO-CERTIFICATES'));
    }
}
