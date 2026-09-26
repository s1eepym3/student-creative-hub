<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SkillRequest;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(): View
    {
        $skills = Skill::orderBy('kategori')->orderBy('nama_skill')->paginate(10);
        return view('admin.skills.index', compact('skills'));
    }

    public function create(): View
    {
        $categories = [
            'Programming',
            'Database',
            'UI/UX Design',
            'Graphic Design',
            'Multimedia',
            'Networking',
            'Cloud Computing',
            'Cyber Security',
            'Soft Skills',
        ];
        return view('admin.skills.create', compact('categories'));
    }

    public function store(SkillRequest $request): RedirectResponse
    {
        Skill::create($request->validated());
        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill berhasil ditambahkan.');
    }

    public function edit(Skill $skill): View
    {
        $categories = [
            'Programming',
            'Database',
            'UI/UX Design',
            'Graphic Design',
            'Multimedia',
            'Networking',
            'Cloud Computing',
            'Cyber Security',
            'Soft Skills',
        ];
        return view('admin.skills.edit', compact('skill', 'categories'));
    }

    public function update(SkillRequest $request, Skill $skill): RedirectResponse
    {
        $skill->update($request->validated());
        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill berhasil diperbarui.');
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        if ($skill->mahasiswas()->exists()) {
            return redirect()->route('admin.skills.index')
                ->with('error', 'Skill tidak dapat dihapus karena masih digunakan oleh mahasiswa.');
        }

        $skill->delete();
        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill berhasil dihapus.');
    }
}
