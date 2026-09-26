<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isMahasiswa();
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'thumbnail' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],
            'project_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'media' => ['nullable', 'array', 'max:5'],
            'media.*' => ['file', 'mimes:jpg,jpeg,png,mp4', 'max:20480'],
            'visibility' => ['required', Rule::in(['public', 'private'])],
            'technologies' => ['nullable', 'array'],
                        'technologies.*' => ['string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'judul.required' => 'Judul project wajib diisi.',
            'deskripsi.required' => 'Deskripsi project wajib diisi.',
            'thumbnail.required' => 'Thumbnail wajib diunggah untuk project baru.',
            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Thumbnail harus berformat JPG, JPEG, atau PNG.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 5 MB.',
            'project_url.url' => 'Format URL Project tidak valid.',
            'github_url.url' => 'Format URL GitHub tidak valid.',
            'visibility.required' => 'Visibilitas wajib dipilih.',
            'visibility.in' => 'Pilihan visibilitas tidak valid.',
            'technologies.*.max' => 'Nama teknologi maksimal 100 karakter.',
        ];
    }
}
