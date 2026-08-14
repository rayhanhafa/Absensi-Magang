<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMentorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage mentors');
    }

    public function rules(): array
    {
        $mentor = $this->route('mentor');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($mentor->user_id)],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('mentors', 'nip')->ignore($mentor->id)],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'bagian' => ['nullable', 'string', 'max:255'],
        ];
    }
}