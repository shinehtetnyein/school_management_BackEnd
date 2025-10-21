<?php

namespace Modules\Departments\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string',
            'event_type' => 'required|string|in:academic,athletic,cultural,admin,club,assembly,exam,field_trip',
            'grade_level' => 'nullable|array',
            'grade_level.*' => 'string',
            'max_participants' => 'nullable|integer|min:1',
            'requires_permission' => 'boolean',
            'additional_info' => 'nullable|array'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Event title is required',
            'start_date.required' => 'Event start date is required',
            'end_date.after' => 'End date must be after start date',
            'event_type.required' => 'Event type is required',
            'event_type.in' => 'Invalid event type selected',
            'max_participants.min' => 'Maximum participants must be at least 1'
        ];
    }
}
