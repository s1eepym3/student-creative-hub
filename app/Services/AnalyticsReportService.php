<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\Project;
use App\Models\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnalyticsReportService
{
    public function __construct(protected ProfileService $profileService) {}

    /**
     * Get all cached dashboard analytics for a student.
     * Cache duration: 5 minutes.
     *
     * @param Mahasiswa $mahasiswa
     * @return array
     */
    public function getDashboardData(Mahasiswa $mahasiswa): array
    {
        $cacheKey = "analytics_dashboard_{$mahasiswa->id}";

        return Cache::remember($cacheKey, 300, function () use ($mahasiswa) {
            $portfolioGrowth = $this->getPeriodStats($mahasiswa, Mahasiswa::class);
            $projectGrowth = $this->getPeriodStats($mahasiswa, Project::class);

            $totalPortfolioViews = View::where('mahasiswa_id', $mahasiswa->id)
                ->where('viewable_type', Mahasiswa::class)
                ->count();

            $totalProjectViews = View::where('mahasiswa_id', $mahasiswa->id)
                ->where('viewable_type', Project::class)
                ->count();

            $totalQrScans = View::where('mahasiswa_id', $mahasiswa->id)
                ->where('source', 'qr')
                ->count();

            $mostViewedProject = $mahasiswa->projects()
                ->withCount('views')
                ->orderByDesc('views_count')
                ->first();

            $mostLikedProject = $mahasiswa->projects()
                ->withCount('likes')
                ->orderByDesc('likes_count')
                ->first();

            $monthlyTrend = $this->getMonthlyTrend($mahasiswa);
            $categoryDistribution = $this->getCategoryDistribution($mahasiswa);
            $technologyDistribution = $this->getTechnologyDistribution($mahasiswa);
            $insights = $this->generateInsights($mahasiswa, $totalPortfolioViews, $totalProjectViews, $totalQrScans);

            return [
                'totalPortfolioViews'  => $totalPortfolioViews,
                'totalProjectViews'    => $totalProjectViews,
                'totalQrScans'         => $totalQrScans,
                'portfolioGrowth'      => $portfolioGrowth,
                'projectGrowth'        => $projectGrowth,
                'mostViewedProject'    => $mostViewedProject,
                'mostLikedProject'     => $mostLikedProject,
                'monthlyTrend'         => $monthlyTrend,
                'categoryDistribution' => $categoryDistribution,
                'technologyDistribution' => $technologyDistribution,
                'insights'             => $insights,
            ];
        });
    }

    /**
     * Get period stats and calculate growth safely.
     */
    protected function getPeriodStats(Mahasiswa $mahasiswa, string $viewableType): array
    {
        $now = Carbon::now();

        // 1. Today vs Yesterday
        $todayCount = View::where('mahasiswa_id', $mahasiswa->id)
            ->where('viewable_type', $viewableType)
            ->where('created_at', '>=', Carbon::today())
            ->count();

        $yesterdayCount = View::where('mahasiswa_id', $mahasiswa->id)
            ->where('viewable_type', $viewableType)
            ->where('created_at', '>=', Carbon::yesterday())
            ->where('created_at', '<', Carbon::today())
            ->count();

        // 2. This Week vs Last Week
        $thisWeekCount = View::where('mahasiswa_id', $mahasiswa->id)
            ->where('viewable_type', $viewableType)
            ->where('created_at', '>=', $now->copy()->startOfWeek())
            ->count();

        $lastWeekCount = View::where('mahasiswa_id', $mahasiswa->id)
            ->where('viewable_type', $viewableType)
            ->where('created_at', '>=', $now->copy()->subWeek()->startOfWeek())
            ->where('created_at', '<', $now->copy()->startOfWeek())
            ->count();

        // 3. This Month vs Last Month
        $thisMonthCount = View::where('mahasiswa_id', $mahasiswa->id)
            ->where('viewable_type', $viewableType)
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->count();

        $lastMonthCount = View::where('mahasiswa_id', $mahasiswa->id)
            ->where('viewable_type', $viewableType)
            ->where('created_at', '>=', $now->copy()->subMonth()->startOfMonth())
            ->where('created_at', '<', $now->copy()->startOfMonth())
            ->count();

        return [
            'today' => [
                'count'  => $todayCount,
                'growth' => $this->calculateGrowth($todayCount, $yesterdayCount),
            ],
            'week' => [
                'count'  => $thisWeekCount,
                'growth' => $this->calculateGrowth($thisWeekCount, $lastWeekCount),
            ],
            'month' => [
                'count'  => $thisMonthCount,
                'growth' => $this->calculateGrowth($thisMonthCount, $lastMonthCount),
            ],
        ];
    }

    /**
     * Helper to compute growth percentage safely.
     */
    protected function calculateGrowth(int $current, int $previous): array
    {
        if ($previous === 0) {
            return [
                'text'      => $current > 0 ? 'New' : 'N/A',
                'direction' => $current > 0 ? 'up' : 'none',
            ];
        }

        $percentage = (($current - $previous) / $previous) * 100;
        $rounded = round($percentage, 0);

        if ($rounded > 0) {
            return [
                'text'      => '↑ ' . $rounded . '%',
                'direction' => 'up',
            ];
        } elseif ($rounded < 0) {
            return [
                'text'      => '↓ ' . abs($rounded) . '%',
                'direction' => 'down',
            ];
        }

        return [
            'text'      => '0%',
            'direction' => 'none',
        ];
    }

    /**
     * Get monthly visitor trend for the last 6 months (handles months with zero traffic).
     */
    protected function getMonthlyTrend(Mahasiswa $mahasiswa): array
    {
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();

        $rawResults = View::where('mahasiswa_id', $mahasiswa->id)
            ->where('created_at', '>=', $sixMonthsAgo)
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_key"),
                DB::raw("SUM(CASE WHEN viewable_type = '" . Mahasiswa::class . "' THEN 1 ELSE 0 END) as portfolio_count"),
                DB::raw("SUM(CASE WHEN viewable_type = '" . Project::class . "' THEN 1 ELSE 0 END) as project_count"),
                DB::raw("SUM(CASE WHEN source = 'qr' THEN 1 ELSE 0 END) as qr_count")
            )
            ->groupBy('month_key')
            ->get()
            ->keyBy('month_key');

        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $key = $month->format('Y-m');
            
            $dbRow = $rawResults->get($key);

            $chartData[] = [
                'label'     => $month->translatedFormat('M Y'),
                'portfolio' => $dbRow ? (int)$dbRow->portfolio_count : 0,
                'project'   => $dbRow ? (int)$dbRow->project_count : 0,
                'qr'        => $dbRow ? (int)$dbRow->qr_count : 0,
            ];
        }

        return [
            'labels'    => array_column($chartData, 'label'),
            'portfolio' => array_column($chartData, 'portfolio'),
            'project'   => array_column($chartData, 'project'),
            'qr'        => array_column($chartData, 'qr'),
        ];
    }

    /**
     * Get view distribution per category.
     */
    protected function getCategoryDistribution(Mahasiswa $mahasiswa): array
    {
        $data = DB::table('views')
            ->join('projects', 'views.viewable_id', '=', 'projects.id')
            ->join('categories', 'projects.category_id', '=', 'categories.id')
            ->where('views.mahasiswa_id', $mahasiswa->id)
            ->where('views.viewable_type', Project::class)
            ->groupBy('categories.nama_kategori')
            ->select('categories.nama_kategori as label', DB::raw('count(views.id) as count'))
            ->orderByDesc('count')
            ->get();

        return [
            'labels' => $data->pluck('label')->toArray(),
            'values' => $data->pluck('count')->map(fn($v) => (int)$v)->toArray(),
        ];
    }

    /**
     * Get view distribution per technology.
     */
    protected function getTechnologyDistribution(Mahasiswa $mahasiswa): array
    {
        $data = DB::table('views')
            ->join('projects', 'views.viewable_id', '=', 'projects.id')
            ->join('project_technologies', 'projects.id', '=', 'project_technologies.project_id')
            ->where('views.mahasiswa_id', $mahasiswa->id)
            ->where('views.viewable_type', Project::class)
            ->groupBy('project_technologies.technology_name')
            ->select('project_technologies.technology_name as label', DB::raw('count(views.id) as count'))
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return [
            'labels' => $data->pluck('label')->toArray(),
            'values' => $data->pluck('count')->map(fn($v) => (int)$v)->toArray(),
        ];
    }

    /**
     * Rule-based engine to generate actionable recommendations.
     */
    protected function generateInsights(Mahasiswa $mahasiswa, int $portfolioViews, int $projectViews, int $qrScans): array
    {
        $insights = [];

        // INS-LOW-COMPLETION
        $completion = $this->profileService->completionPercentage($mahasiswa);
        if ($completion < 80) {
            $insights[] = [
                'rule_id'     => 'INS-LOW-COMPLETION',
                'title'       => 'Lengkapi Profil Anda',
                'text'        => "Kelengkapan profil Anda saat ini baru {$completion}%. Lengkapi data diri, foto, dan CV untuk meningkatkan visibilitas di explore.",
                'improvement' => 'Meningkatkan tingkat penemuan profil oleh pencari kerja.',
                'type'        => 'warning',
            ];
        }

        // INS-LOW-PROJECTS
        $approvedProjectsCount = $mahasiswa->projects()->where('status', 'approved')->count();
        if ($approvedProjectsCount < 3) {
            $insights[] = [
                'rule_id'     => 'INS-LOW-PROJECTS',
                'title'       => 'Tambahkan Lebih Banyak Proyek',
                'text'        => "Anda baru memiliki {$approvedProjectsCount} project yang disetujui. Publikasikan minimal 3 karya untuk portofolio yang meyakinkan.",
                'improvement' => 'Membangun portofolio yang kredibel di mata publik.',
                'type'        => 'info',
            ];
        }

        // INS-NO-QR-SCANS
        if ($qrScans === 0 && $mahasiswa->qrPortfolio()->exists()) {
            $insights[] = [
                'rule_id'     => 'INS-NO-QR-SCANS',
                'title'       => 'Promosikan Kode QR Anda',
                'text'        => 'Kode QR Anda belum pernah di-scan. Cetak kode QR Anda di CV atau kartu nama untuk menarik kunjungan langsung.',
                'improvement' => 'Meningkatkan kunjungan langsung dari media fisik.',
                'type'        => 'info',
            ];
        }

        // INS-NO-CERTIFICATES
        $certificatesCount = $mahasiswa->certificates()->count();
        if ($certificatesCount === 0) {
            $insights[] = [
                'rule_id'     => 'INS-NO-CERTIFICATES',
                'title'       => 'Unggah Sertifikat Kompetensi',
                'text'        => 'Anda belum menambahkan sertifikat kompetensi. Unggah sertifikat kursus atau kompetensi untuk memperkuat klaim keahlian.',
                'improvement' => 'Menambah bukti keabsahan keahlian mahasiswa.',
                'type'        => 'warning',
            ];
        }

        // INS-TOP-TECH (Triggered if projectViews > 10 and one tech makes up >= 50% of views)
        if ($projectViews > 10) {
            $topTechData = DB::table('views')
                ->join('projects', 'views.viewable_id', '=', 'projects.id')
                ->join('project_technologies', 'projects.id', '=', 'project_technologies.project_id')
                ->where('views.mahasiswa_id', $mahasiswa->id)
                ->where('views.viewable_type', Project::class)
                ->groupBy('project_technologies.technology_name')
                ->select('project_technologies.technology_name as label', DB::raw('count(views.id) as count'))
                ->orderByDesc('count')
                ->first();

            if ($topTechData) {
                $percentage = round(($topTechData->count / $projectViews) * 100);
                if ($percentage >= 50) {
                    $insights[] = [
                        'rule_id'     => 'INS-TOP-TECH',
                        'title'       => 'Fokus Teknologi yang Sukses',
                        'text'        => "Proyek yang menggunakan teknologi '{$topTechData->label}' menerima {$percentage}% dari seluruh kunjungan project Anda. Pertimbangkan menambahkan variasi karya serupa.",
                        'improvement' => 'Memperkuat personal branding sebagai spesialis di bidang tersebut.',
                        'type'        => 'success',
                    ];
                }
            }
        }

        return $insights;
    }
}
