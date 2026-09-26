<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\ProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(protected ProfileService $profileService) {}

    public function edit(): View
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $completion = $this->profileService->completionPercentage($mahasiswa);
        return view('mahasiswa.profile.edit', compact('mahasiswa', 'completion'));
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $data = $request->validated();
        $photo = $request->file('foto_profil');
        $cv = $request->file('cv_file');

        $this->profileService->update($mahasiswa, $data, $photo, $cv);

        return redirect()->route('mahasiswa.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
