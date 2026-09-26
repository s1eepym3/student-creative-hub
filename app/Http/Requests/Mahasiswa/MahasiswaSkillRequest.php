<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MahasiswaSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isMahasiswa();
    }

    public function rules(): array
    {
        $rules = [
            'level' => ['required', Rule::in(['Beginner', 'Intermediate', 'Advanced'])],
        ];

        if ($this->isMethod('POST')) {
            $rules['skill_id'] = ['required', 'exists:skills,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'skill_id.required' => 'Skill wajib dipilih.',
            'skill_id.exists' => 'Skill tidak ditemukan.',
            'level.required' => 'Level skill wajib dipilih.',
            'level.in' => 'Level harus Beginner, Intermediate, atau Advanced.',
        ];
    }
}
