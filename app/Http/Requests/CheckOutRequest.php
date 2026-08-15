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
        $requireLocation = config('attendance.require_location');
        $presence = $requireLocation ? 'required' : 'nullable';

        return [
            'latitude' => [$presence, 'numeric', 'between:-90,90'],
            'longitude' => [$presence, 'numeric', 'between:-180,180'],
            'accuracy' => [$presence, 'numeric', 'min:0'],
            'foto_check_out' => [$presence, 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.required' => 'Lokasi diperlukan untuk melakukan absensi. Silakan izinkan akses lokasi pada browser.',
            'longitude.required' => 'Lokasi diperlukan untuk melakukan absensi. Silakan izinkan akses lokasi pada browser.',
            'accuracy.required' => 'Lokasi diperlukan untuk melakukan absensi. Silakan izinkan akses lokasi pada browser.',
            'foto_check_out.required' => 'Selfie diperlukan untuk melakukan absensi.',
            'foto_check_out.image' => 'File harus berupa gambar.',
            'foto_check_out.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
            'foto_check_out.max' => 'Ukuran foto maksimal 5MB.',
        ];
    }
}