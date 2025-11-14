<?php

namespace Modules\Users\Teachers\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'Course ID is required',
            'course_id.exists' => 'Course does not exist',
        ];
    }
}
