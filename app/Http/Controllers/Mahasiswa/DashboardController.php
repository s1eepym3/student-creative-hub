<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Services\ProfileService;
use App\Services\AnalyticsReportService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected ProfileService $profileService,
        protected AnalyticsReportService $analyticsReportService
    ) {}

    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        $profileCompletion = $this->profileService->completionPercentage($mahasiswa);
        $totalProjects = $mahasiswa->projects()->count();
        $totalCertificates = $mahasiswa->certificates()->count();
        $totalAchievements = $mahasiswa->achievements()->count();
        $totalSkills = $mahasiswa->skills()->count();

        // Get cached portfolio analytics data
        $analytics = $this->analyticsReportService->getDashboardData($mahasiswa);

        return view('mahasiswa.dashboard', array_merge([
            'user' => $user,
            'mahasiswa' => $mahasiswa,
            'profileCompletion' => $profileCompletion,
            'totalProjects' => $totalProjects,
            'totalCertificates' => $totalCertificates,
            'totalAchievements' => $totalAchievements,
            'totalSkills' => $totalSkills,
        ], $analytics));
    }
}
