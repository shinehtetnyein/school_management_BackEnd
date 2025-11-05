<?php
// Modules/Subject/app/Http/Requests/StoreSubjectRequest.php

namespace Modules\Subject\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_code' => 'required|string|max:50|unique:subjects,subject_code',
            'subject_name' => 'required|string|max:255',
            'subject_desc' => 'nullable|string',
            'class_level' => 'required|in:beginner,intermediate,advanced',
            'status' => 'required|in:active,inactive',
            'exam_id' => 'nullable|exists:exams,id'
        ];
    }

    public function messages(): array
    {
        return [
            'subject_code.unique' => 'A subject with this code already exists.',
            'class_level.in' => 'Class level must be one of: beginner, intermediate, advanced.',
            'status.in' => 'Status must be either active or inactive.',
        ];
    }
}
