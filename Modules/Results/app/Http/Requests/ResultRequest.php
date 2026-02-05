<?php

namespace Modules\Results\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResultRequest extends FormRequest
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
            'student_id' => 'required|exists:users,id',
            'exam_id' => 'required|exists:exams,id',
            'course_id' => 'required|exists:courses,id',
            'marks' => 'required|numeric',
            'grade' => 'nullable|string',
            'status' => 'required|in:Pass,Fail',
            'remarks' => 'nullable|string',
            'date' => 'required|date',
        ];
    }
}
