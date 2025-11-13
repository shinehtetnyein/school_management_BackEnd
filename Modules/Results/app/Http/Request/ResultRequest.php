<?php
namespace Modules\Results\app\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class ResultRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'required|integer',
            'exam_id' => 'required|integer',
            'course_id' => 'required|integer',
            'marks' => 'required|numeric|min:0',
            'grade' => 'nullable|string',
            'status' => 'required|string',
            'remarks' => 'nullable|string',
            'date' => 'required|date',
        ];
    }
}
