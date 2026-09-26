<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\MahasiswaSkillRequest;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(): View
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $mahasiswaSkills = $mahasiswa->skills()->orderBy('nama_skill')->get();
        return view('mahasiswa.skills.index', compact('mahasiswaSkills'));
    }

    public function create(): View
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $assignedSkillIds = $mahasiswa->skills()->pluck('skills.id')->toArray();
        $availableSkills = Skill::whereNotIn('id', $assignedSkillIds)
            ->orderBy('kategori')
            ->orderBy('nama_skill')
            ->get();

        return view('mahasiswa.skills.create', compact('availableSkills'));
    }

    public function store(MahasiswaSkillRequest $request): RedirectResponse
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $mahasiswa->skills()->attach($request->skill_id, [
            'level' => $request->level,
        ]);

        return redirect()->route('mahasiswa.skills.index')
            ->with('success', 'Skill berhasil ditambahkan.');
    }

    public function edit(Skill $skill): View
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $pivot = $mahasiswa->skills()->where('skill_id', $skill->id)->firstOrFail();
        return view('mahasiswa.skills.edit', compact('skill', 'pivot'));
    }

    public function update(MahasiswaSkillRequest $request, Skill $skill): RedirectResponse
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $mahasiswa->skills()->updateExistingPivot($skill->id, [
            'level' => $request->level,
        ]);

        return redirect()->route('mahasiswa.skills.index')
            ->with('success', 'Level skill berhasil diperbarui.');
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $mahasiswa->skills()->detach($skill->id);

        return redirect()->route('mahasiswa.skills.index')
            ->with('success', 'Skill berhasil dihapus.');
    }
}
