<?php

namespace Modules\Users\Students\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
        $studentId = $this->route('id');

        return [
            'name' => 'sometimes|string|max:255',
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $studentId,
            'roll_no' => 'sometimes|string|unique:users,roll_no,' . $studentId,
            'phone_no' => 'sometimes|string|max:20',
            'status' => 'sometimes|in:active,inactive,suspended',
            'enrollment_date' => 'sometimes|nullable|date',
        ];
    }
}
