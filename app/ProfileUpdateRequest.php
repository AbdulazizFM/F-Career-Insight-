<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = session('user_id');
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:USER,email,' . $userId . ',user_id'],
            'job_title' => ['nullable', 'string', 'max:150'],
            'company' => ['nullable', 'string', 'max:150'],
            'years_experience' => ['nullable', 'integer', 'min:0', 'max:50'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ];
    }
}
