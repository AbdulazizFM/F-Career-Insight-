<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sub_major_id' => ['nullable', 'exists:SUBMAJOR,sub_major_id'],
            'role_id' => ['nullable', 'exists:ROLES,role_id', 'required_without:sub_major_id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'advantages' => ['required', 'string'],
            'disadvantages' => ['required', 'string'],
            'experience' => ['required', 'string'],
        ];
    }
}
