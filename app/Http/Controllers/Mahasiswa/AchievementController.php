<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\AchievementRequest;
use App\Models\Achievement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AchievementController extends Controller
{
    public function index(): View
    {
        $achievements = Auth::user()->mahasiswa->achievements()->orderBy('tahun', 'desc')->get();
        return view('mahasiswa.achievements.index', compact('achievements'));
    }

    public function create(): View
    {
        return view('mahasiswa.achievements.create');
    }

    public function store(AchievementRequest $request): RedirectResponse
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $mahasiswa->achievements()->create($request->validated());

        return redirect()->route('mahasiswa.achievements.index')
            ->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function edit(Achievement $achievement): View
    {
        $this->authorizeAchievement($achievement);
        return view('mahasiswa.achievements.edit', compact('achievement'));
    }

    public function update(AchievementRequest $request, Achievement $achievement): RedirectResponse
    {
        $this->authorizeAchievement($achievement);
        $achievement->update($request->validated());

        return redirect()->route('mahasiswa.achievements.index')
            ->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy(Achievement $achievement): RedirectResponse
    {
        $this->authorizeAchievement($achievement);
        $achievement->delete();

        return redirect()->route('mahasiswa.achievements.index')
            ->with('success', 'Prestasi berhasil dihapus.');
    }

    private function authorizeAchievement(Achievement $achievement): void
    {
        if ($achievement->mahasiswa_id !== Auth::user()->mahasiswa->id) {
            abort(403, 'Anda tidak memiliki akses ke prestasi ini.');
        }
    }
}
