<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class AchievementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isMahasiswa();
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:50'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . now()->year],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul prestasi wajib diisi.',
            'level.required' => 'Level prestasi wajib diisi.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
        ];
    }
}
