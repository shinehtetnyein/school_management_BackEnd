<?php

namespace Modules\Exams\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'exam_date' => 'required|date',
            'duration' => 'nullable|integer',
            'total_marks' => 'required|numeric',
            'passing_marks' => 'required|numeric',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
            'type' => 'nullable|string',
        ];
    }
}
