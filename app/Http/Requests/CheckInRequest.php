<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya intern aktif yang boleh check-in.
        // Pengecekan "sudah ada absensi hari ini" dilakukan di Service,
        // bukan di sini, karena itu business logic bukan validasi input murni.
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
            'foto_check_in' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'foto_check_in.image' => 'File harus berupa gambar.',
            'foto_check_in.mimes' => 'Format foto harus jpg, jpeg, atau png.',
            'foto_check_in.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}