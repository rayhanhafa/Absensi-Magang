<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeaveRequestRequest extends FormRequest
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
            'jenis' => ['required', Rule::in(['izin', 'sakit'])],
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alasan' => ['required', 'string', 'min:10', 'max:1000'],
            'bukti' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'alasan.min' => 'Alasan minimal 10 karakter, mohon dijelaskan lebih detail.',
            'bukti.mimes' => 'Bukti harus berupa file jpg, jpeg, png, atau pdf.',
            'bukti.max' => 'Ukuran file maksimal 2MB.',
        ];
    }
}