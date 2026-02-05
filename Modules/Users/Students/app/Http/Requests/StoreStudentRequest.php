<?php

namespace Modules\Users\Students\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
        'roll_no'          => 'sometimes|string|unique:users,roll_no,' . $this->id,
        'first_name'       => 'sometimes|string|max:255',
        'last_name'        => 'sometimes|string|max:255',
        'email'            => 'sometimes|email|unique:users,email,' . $this->id,
        'password'         => 'sometimes|string|min:8',
        'phone_no'         => 'sometimes|string|max:20',
        'enrollment_date' => 'sometimes|date',
        'date_of_birth'    => 'sometimes|date|before:today',
        'profile_photo'    => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
        'nrc'              => 'sometimes|nullable|string|max:50',
        'religion'         => 'sometimes|nullable|string|max:100',
        'mother_tongue'    => 'sometimes|nullable|string|max:100',
        'language'         => 'sometimes|nullable|string|max:100',
        'gender'           => 'sometimes|nullable|in:male,female,other',
        'parent_id'        => 'sometimes|exists:users,id',
        'course_id'        => 'sometimes|exists:courses,id',
        'subject_id'       => 'sometimes|exists:subjects,id',
        'section_id'       => 'sometimes|exists:sections,id',
        'classroom_id'     => 'sometimes|exists:classrooms,id',
        'enrollment_date'  => 'sometimes|nullable|date',
        'status'           => 'sometimes|in:active,inactive,suspended',
    ];
}

}
