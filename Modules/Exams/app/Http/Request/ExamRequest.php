<?php

namespace Modules\Exams\app\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'course_id' => 'required|integer|exists:courses,id',
            'exam_date' => 'required|date',
            'duration' => 'sometimes|nullable|integer',
            'total_marks' => 'sometimes|nullable|numeric',
            'passing_marks' => 'sometimes|nullable|numeric',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|boolean',
            'type' => 'sometimes|nullable|string',
        ];
    }
}
