<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isMahasiswa();
    }

    public function rules(): array
    {
        return [
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'penyelenggara' => ['required', 'string', 'max:255'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . now()->year],
            'file_sertifikat' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
            'penyelenggara.required' => 'Penyelenggara wajib diisi.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'file_sertifikat.required' => 'File sertifikat wajib diunggah.',
            'file_sertifikat.mimes' => 'File sertifikat harus berformat JPG, JPEG, PNG, atau PDF.',
            'file_sertifikat.max' => 'Ukuran file sertifikat maksimal 5 MB.',
        ];
    }
}
