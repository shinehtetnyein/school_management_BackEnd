<?php

namespace Modules\TimeTable\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTimeTableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole(['root_admin', 'admin', 'teacher']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'classroom_id' => 'sometimes|required|exists:classrooms,id',
            'section_id' => 'sometimes|required|exists:sections,id',
            'course_id' => 'sometimes|required|exists:courses,id',
            'day_of_week' => 'sometimes|required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'sometimes|required|date_format:H:i',
            'end_time' => 'sometimes|required|date_format:H:i|after:start_time',
            'subject_id' => 'nullable|exists:subjects,id',
            'teacher_id' => 'nullable|exists:users,id',
            'room_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'status' => 'nullable|in:active,inactive',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'end_time.after' => 'The end time must be after the start time.',
        ];
    }
}
