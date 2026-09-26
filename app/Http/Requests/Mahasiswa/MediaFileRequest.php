<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class MediaFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isMahasiswa();
    }

    public function rules(): array
    {
        return [
            'file_media' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,mp4',
                'max:20480', // 20 MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file_media.required' => 'File media wajib diunggah.',
            'file_media.file' => 'Upload harus berupa file.',
            'file_media.mimes' => 'File media harus berformat JPG, JPEG, PNG, atau MP4.',
            'file_media.max' => 'Ukuran file media maksimal 20 MB.',
        ];
    }
}
