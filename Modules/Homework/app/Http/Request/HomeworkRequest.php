<?php

namespace Modules\Homework\app\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class HomeworkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'course_id' => 'required|integer|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'due_date' => 'sometimes|nullable|date',
        ];
    }
}
