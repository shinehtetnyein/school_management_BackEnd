<?php

namespace Modules\Subject\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $subjectId = $this->route('subject'); // Assuming the route parameter is named 'subject'

        return [
            'subject_code' => 'sometimes|string|max:255|unique:subjects,subject_code,' . $subjectId,
            'subject_name' => 'sometimes|string|max:255',
            'subject_desc' => 'nullable|string',
            'class_level' => 'sometimes|in:beginner,intermediate,advanced',
            'status' => 'sometimes|in:active,inactive',
        ];
    }
}
