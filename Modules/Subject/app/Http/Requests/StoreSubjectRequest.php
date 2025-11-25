<?php

namespace Modules\Subject\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
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
        return [
            'subject_code' => 'required|string|max:255|unique:subjects,subject_code',
            'subject_name' => 'required|string|max:255',
            'subject_desc' => 'nullable|string',
            'class_level' => 'required|in:beginner,intermediate,advanced',
            'status' => 'required|in:active,inactive',
        ];
    }
}
