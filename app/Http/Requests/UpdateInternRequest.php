<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInternRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage interns');
    }

    public function rules(): array
    {
        // route model binding: {intern}
        $intern = $this->route('intern');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($intern->user_id)],
            'nomor_induk' => ['nullable', 'string', 'max:50', Rule::unique('profiles', 'nomor_induk')->ignore($intern->id, 'user_id')],
            'nomor_hp' => ['nullable', 'string', 'max:20'],
            'universitas' => ['required', 'string', 'max:255'],
            'jurusan' => ['required', 'string', 'max:255'],
            'mentor_id' => ['nullable', 'exists:mentors,id'],
            'internship_period_id' => ['required', 'exists:internship_periods,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'status' => ['required', Rule::in(['aktif', 'selesai', 'nonaktif'])],
        ];
    }
}