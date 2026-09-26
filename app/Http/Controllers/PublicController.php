<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Mahasiswa;
use App\Models\Project;
use App\Models\Showcase;
use App\Models\Skill;
use Illuminate\Http\Request;
use App\Events\ViewRecorded;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    /**
     * Homepage displaying latest showcase projects.
     */
    public function index()
    {
        $showcases = Showcase::with(['project.mahasiswa'])
            ->whereHas('project', function ($q) {
                $q->public();
            })
            ->latest('featured_at')
            ->take(6)
            ->get();

        return view('welcome', compact('showcases'));
    }

    /**
     * Explore page (Advanced Search).
     */
    public function explore(Request $request)
    {
        $query = Project::public()->with(['mahasiswa', 'category', 'technologies'])->withCount('likes');

        // Search by keyword (Project Title, Student Name, Technology)
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhereHas('mahasiswa', function ($q2) use ($keyword) {
                      $q2->where('nama_lengkap', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('technologies', function ($q3) use ($keyword) {
                      $q3->where('technology_name', 'like', "%{$keyword}%");
                  });
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by student skill
        if ($request->filled('skill')) {
            $skillId = $request->skill;
            $query->whereHas('mahasiswa.skills', function ($q) use ($skillId) {
                $q->where('skills.id', $skillId);
            });
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'most_liked') {
            $query->orderByDesc('likes_count')->latest();
        } else {
            $query->latest();
        }

        $projects = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('nama_kategori')->get();
        $skills = Skill::orderBy('nama_skill')->get();

        return view('public.explore', compact('projects', 'categories', 'skills'));
    }

    /**
     * Dedicated showcase page.
     */
    public function showcase()
    {
        $showcases = Showcase::with(['project.mahasiswa', 'project.category'])
            ->withCount('project as likes_count')
            ->whereHas('project', function ($q) {
                $q->public();
            })
            ->latest('featured_at')
            ->paginate(12);

        // Load likes count for projects in showcases
        $showcases->getCollection()->transform(function ($showcase) {
            $showcase->project->loadCount('likes');
            return $showcase;
        });

        return view('public.showcase', compact('showcases'));
    }

    /**
     * Detail project page.
     */
    public function projectDetail($slug)
    {
        $project = Project::public()
            ->with(['mahasiswa', 'category', 'mediaFiles', 'technologies'])
            ->withCount('likes')
            ->where('slug', $slug)
            ->firstOrFail();

        // Check if current visitor has liked
        $visitorIp = request()->ip();
        $hasLiked = $project->likes()->where('visitor_ip', $visitorIp)->exists();

        // Dispatch event to track view (exclude self-view)
        if (!Auth::check() || Auth::user()->mahasiswa?->id !== $project->mahasiswa_id) {
            $source = 'direct';
            $referer = request()->headers->get('referer');
            if ($referer && str_contains($referer, request()->getHost())) {
                $source = 'internal';
            } elseif ($referer) {
                $source = 'external';
            }
            event(new ViewRecorded($project, request()->ip(), request()->userAgent(), $source));
        }

        return view('public.project-detail', compact('project', 'hasLiked'));
    }

    /**
     * Public profile page.
     */
    public function profile($slug)
    {
        $mahasiswa = Mahasiswa::with([
            'projects' => function ($q) {
                $q->public()->withCount('likes')->latest();
            },
            'achievements',
            'certificates',
            'skills'
        ])->where('slug', $slug)->firstOrFail();

        // Dispatch event to track view (exclude self-view)
        if (!Auth::check() || Auth::user()->mahasiswa?->id !== $mahasiswa->id) {
            $source = request()->query('ref') === 'qr' ? 'qr' : 'direct';
            if ($source === 'direct') {
                $referer = request()->headers->get('referer');
                if ($referer && str_contains($referer, request()->getHost())) {
                    $source = 'internal';
                } elseif ($referer) {
                    $source = 'external';
                }
            }
            event(new ViewRecorded($mahasiswa, request()->ip(), request()->userAgent(), $source));
        }

        return view('public.profile', compact('mahasiswa'));
    }
}
