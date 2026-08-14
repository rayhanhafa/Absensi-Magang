<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeaveRequestStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya mentor (yang membimbing intern terkait) atau admin.
        // Pengecekan "apakah mentor ini membimbing intern tersebut"
        // dilakukan di Policy (LeaveRequestPolicy), bukan di sini,
        // karena itu bergantung pada record spesifik (route model binding),
        // sementara authorize() FormRequest idealnya untuk cek role umum.
        return $this->user()->hasRole(['admin', 'mentor']);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'catatan_approval' => ['required_if:status,rejected', 'nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'catatan_approval.required_if' => 'Alasan penolakan wajib diisi.',
        ];
    }
}