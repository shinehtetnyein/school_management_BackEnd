<?php

namespace Modules\Departments\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:departments,code' . ($this->department ? ',' . $this->department : ''),
            'type' => 'required|string|in:academic,administrative,support',
            'description' => 'nullable|string',
            'head_of_department' => 'nullable|exists:users,id',
            'location' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'grade_levels' => 'nullable|array',
            'grade_levels.*' => 'string',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'office_hours' => 'nullable|json'
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Department name is required',
            'code.required' => 'Department code is required',
            'code.unique' => 'This department code is already in use',
            'type.required' => 'Department type is required',
            'type.in' => 'Department type must be academic, administrative, or support',
            'head_of_department.exists' => 'Selected head of department does not exist',
            'contact_email.email' => 'Please provide a valid email address',
            'grade_levels.array' => 'Grade levels must be provided as a list',
            'subjects.*.exists' => 'One or more selected subjects do not exist'
        ];
    }
}
