<?php

namespace Modules\Users\Teachers\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'subject_id.required' => 'Subject ID is required',
            'subject_id.exists' => 'Subject does not exist',
        ];
    }
}
