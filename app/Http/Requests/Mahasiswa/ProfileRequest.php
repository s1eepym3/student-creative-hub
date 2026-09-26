<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isMahasiswa();
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'prodi' => ['nullable', 'string', 'max:100'],
            'angkatan' => ['nullable', 'string', 'max:10'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'github' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'bio.max' => 'Bio maksimal 1000 karakter.',
            'github.url' => 'URL GitHub tidak valid.',
            'linkedin.url' => 'URL LinkedIn tidak valid.',
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.mimes' => 'Foto profil harus berformat JPG, JPEG, atau PNG.',
            'foto_profil.max' => 'Ukuran foto profil maksimal 5 MB.',
            'cv_file.mimes' => 'CV harus berformat PDF.',
            'cv_file.max' => 'Ukuran CV maksimal 5 MB.',
        ];
    }
}
