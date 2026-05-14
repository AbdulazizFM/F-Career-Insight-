<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'thread_id' => ['nullable', 'exists:MESSAGETHREAD,thread_id'],
            'evaluation_id' => ['nullable', 'exists:EVALUATION,evaluation_id'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body_text' => ['required', 'string'],
        ];
    }
}
