<?php
// Modules/Course/app/Http/Request/UpdateCourseRequest.php

namespace Modules\Course\App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Course\Models\Course;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $courseId = $this->route('course') ?? $this->route('id');

        return [
            'course_name' => 'sometimes|required|string|max:255|unique:courses,course_name,' . $courseId,
            'description' => 'nullable|string',
            'category' => 'sometimes|required|string|max:255'
        ];
    }
}
