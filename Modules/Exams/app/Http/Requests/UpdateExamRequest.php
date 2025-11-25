<?php

namespace Modules\Exams\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamRequest extends FormRequest
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
            'title' => 'sometimes|string|max:255',
            'course_id' => 'sometimes|exists:courses,id',
            'exam_date' => 'sometimes|date',
            'duration' => 'nullable|integer',
            'total_marks' => 'sometimes|numeric',
            'passing_marks' => 'sometimes|numeric',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
            'type' => 'nullable|string',
        ];
    }
}
