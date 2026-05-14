<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'department' => ['nullable', 'string', 'max:100'],
            'access_level' => ['nullable', 'string', 'max:50'],
            'security_clearance_level' => ['nullable', 'integer', 'min:0', 'max:10'],
            'two_factor_enabled' => ['nullable', 'boolean'],
        ];
    }
}
