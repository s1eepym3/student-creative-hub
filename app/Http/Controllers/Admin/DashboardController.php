<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary Cards
        $totalUsers = User::count();
        $totalProjects = Project::count();
        $pendingProjects = Project::where('status', 'pending')->count();
        $approvedProjects = Project::where('status', 'approved')->count();

        // Project Status Distribution (for Doughnut Chart)
        $projectStatusStats = Project::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Ensure all statuses have at least 0
        $projectStatusData = [
            'approved' => $projectStatusStats['approved'] ?? 0,
            'pending' => $projectStatusStats['pending'] ?? 0,
            'rejected' => $projectStatusStats['rejected'] ?? 0,
            'draft' => $projectStatusStats['draft'] ?? 0,
        ];

        // Projects per Category (for Bar Chart)
        $categoryStats = DB::table('projects')
            ->join('categories', 'projects.category_id', '=', 'categories.id')
            ->select('categories.nama_kategori', DB::raw('count(projects.id) as total'))
            ->whereNull('projects.deleted_at')
            ->groupBy('categories.id', 'categories.nama_kategori')
            ->orderByDesc('total')
            ->limit(7)
            ->get();

        $categoryLabels = $categoryStats->pluck('nama_kategori')->toArray();
        $categoryData = $categoryStats->pluck('total')->toArray();

        // Top 5 Most Liked Projects
        $topProjects = Project::with(['mahasiswa', 'category'])
            ->withCount('likes')
            ->where('status', 'approved')
            ->where('visibility', 'public')
            ->orderByDesc('likes_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalProjects', 'pendingProjects', 'approvedProjects',
            'projectStatusData', 'categoryLabels', 'categoryData', 'topProjects'
        ));
    }
}

