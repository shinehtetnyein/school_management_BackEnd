<?php
// Modules/Course/app/Http/Requests/StoreCourseRequest.php

namespace Modules\Course\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_name' => 'required|string|max:255|unique:courses,course_name',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'course_name.unique' => 'A course with this name already exists.',
        ];
    }
}
