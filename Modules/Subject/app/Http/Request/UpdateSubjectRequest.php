<?php
// Modules/Subject/app/Http/Requests/UpdateSubjectRequest.php

namespace Modules\Subject\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class UpdateSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $subjectId = $this->route('subject') ?? $this->route('id');

        return [
            'subject_code' => 'sometimes|required|string|max:50|unique:subjects,subject_code,' . $subjectId,
            'subject_name' => 'sometimes|required|string|max:255',
            'subject_desc' => 'nullable|string',
            'class_level' => 'sometimes|required|in:beginner,intermediate,advanced',
            'status' => 'sometimes|required|in:active,inactive',
            'exam_id' => 'nullable|exists:exams,id'
        ];
    }
}
