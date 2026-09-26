<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $skillId = $this->route('skill')?->id;

        return [
            'nama_skill' => [
                'required',
                'string',
                'max:100',
                'unique:skills,nama_skill,' . $skillId,
            ],
            'kategori' => [
                'required',
                Rule::in([
                    'Programming',
                    'Database',
                    'UI/UX Design',
                    'Graphic Design',
                    'Multimedia',
                    'Networking',
                    'Cloud Computing',
                    'Cyber Security',
                    'Soft Skills',
                ]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_skill.required' => 'Nama skill wajib diisi.',
            'nama_skill.unique' => 'Nama skill sudah ada.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori tidak valid.',
        ];
    }
}
