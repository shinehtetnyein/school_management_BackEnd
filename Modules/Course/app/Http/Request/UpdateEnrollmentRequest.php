<?php
// Modules/Course/app/Http/Requests/UpdateEnrollmentRequest.php

namespace Modules\Course\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'sometimes|required|in:active,completed,dropped',
            'class_level' => 'sometimes|required|string|max:255'
        ];
    }
}
