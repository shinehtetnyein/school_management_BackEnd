<?php

namespace Modules\Exams\app\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class SubmitExamRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'required|integer|exists:users,id',
            'answers' => 'sometimes|array',
            'answers.*' => 'sometimes',
            'file' => 'sometimes|file',
            'submitted_at' => 'sometimes|date',
        ];
    }
}
