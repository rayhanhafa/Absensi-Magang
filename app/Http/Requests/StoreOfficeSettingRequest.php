<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfficeSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage office settings');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_meter' => ['required', 'integer', 'min:10', 'max:5000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}