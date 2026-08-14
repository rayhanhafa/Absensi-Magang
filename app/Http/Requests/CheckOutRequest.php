<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckOutRequest extends FormRequest
{
    public function authorize(): bool
    {
        $intern = $this->user()->intern;

        return $this->user()->hasRole('intern')
            && $intern !== null
            && $intern->status === 'aktif';
    }

    public function rules(): array
    {
        return [
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'foto_check_out' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'foto_check_out.image' => 'File harus berupa gambar.',
            'foto_check_out.mimes' => 'Format foto harus jpg, jpeg, atau png.',
            'foto_check_out.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}