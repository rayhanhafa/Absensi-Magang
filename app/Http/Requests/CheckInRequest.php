<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
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
        $requireLocation = config('attendance.require_location');
        $presence = $requireLocation ? 'required' : 'nullable';

        return [
            'latitude' => [$presence, 'numeric', 'between:-90,90'],
            'longitude' => [$presence, 'numeric', 'between:-180,180'],
            'accuracy' => [$presence, 'numeric', 'min:0'],
            'foto_check_in' => [$presence, 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.required' => 'Lokasi diperlukan untuk melakukan absensi. Silakan izinkan akses lokasi pada browser.',
            'longitude.required' => 'Lokasi diperlukan untuk melakukan absensi. Silakan izinkan akses lokasi pada browser.',
            'accuracy.required' => 'Lokasi diperlukan untuk melakukan absensi. Silakan izinkan akses lokasi pada browser.',
            'foto_check_in.required' => 'Selfie diperlukan untuk melakukan absensi.',
            'foto_check_in.image' => 'File harus berupa gambar.',
            'foto_check_in.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
            'foto_check_in.max' => 'Ukuran foto maksimal 5MB.',
        ];
    }
}