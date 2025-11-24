<?php

namespace Modules\Exams\app\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class GradeExamRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'submission_id' => 'required|integer',
            'marks' => 'required|numeric|min:0',
            'grade' => 'sometimes|nullable|string',
            'remarks' => 'sometimes|nullable|string',
        ];
    }
}
