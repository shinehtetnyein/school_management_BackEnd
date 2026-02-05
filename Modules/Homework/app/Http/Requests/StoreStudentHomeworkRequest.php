<?php

namespace Modules\Homework\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentHomeworkRequest extends FormRequest
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
            'homework_id' => 'required|exists:homeworks,id',
            'user_id' => 'required|exists:users,id',
            'file_url' => 'nullable|string',
            'submitted_at' => 'required|date',
        ];
    }
}
