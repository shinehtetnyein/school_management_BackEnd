<?php
// Modules/Course/app/Http/Requests/StoreEnrollmentRequest.php

namespace Modules\Course\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'status' => 'required|in:active,completed,dropped',
            'class_level' => 'required|string|max:255'
        ];
    }
}
