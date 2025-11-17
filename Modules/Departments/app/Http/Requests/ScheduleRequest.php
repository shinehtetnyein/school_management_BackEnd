<?php

namespace Modules\Departments\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_id' => 'required|exists:subjects,id',
            'teacher_uuid' => 'required|exists:users,uuid',
            'grade_level' => 'required|string',
            'section' => 'required|string',
            'day_of_week' => 'required|integer|min:0|max:6',
            'period_type' => 'required|string|in:regular,assembly,exam,activity,homeroom',
            'period_number' => 'required|integer|min:1',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'required|string',
            'substitution_teacher_uuid' => 'nullable|exists:users,uuid',
            'notes' => 'nullable|string',
            'attendance_required' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'subject_id.required' => 'Subject is required',
            'subject_id.exists' => 'Selected subject does not exist',
            'teacher_uuid.required' => 'Teacher is required',
            'teacher_uuid.exists' => 'Selected teacher does not exist',
            'grade_level.required' => 'Grade level is required',
            'section.required' => 'Section is required',
            'day_of_week.required' => 'Day of week is required',
            'day_of_week.between' => 'Day of week must be between 0 (Sunday) and 6 (Saturday)',
            'period_type.required' => 'Period type is required',
            'period_type.in' => 'Invalid period type selected',
            'period_number.required' => 'Period number is required',
            'start_time.required' => 'Start time is required',
            'start_time.date_format' => 'Start time must be in HH:mm format',
            'end_time.required' => 'End time is required',
            'end_time.date_format' => 'End time must be in HH:mm format',
            'end_time.after' => 'End time must be after start time',
            'room.required' => 'Room is required',
            'substitution_teacher_uuid.exists' => 'Selected substitute teacher does not exist'
        ];
    }
}
