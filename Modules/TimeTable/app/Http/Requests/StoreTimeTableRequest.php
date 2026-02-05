<?php

namespace Modules\TimeTable\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Users\Admin\Services\AdminApiServiceInterface;

class StoreTimeTableRequest extends FormRequest
{
        public function __construct(protected AdminApiServiceInterface $adminApiService) {}

    /**
     * Determine if the user is authorized to make this request.
     */
     public function authorize(): bool
    {
        $userId = Auth::user()->id;
        $admin = $this->adminApiService->show($userId);
        if (!$admin) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'classroom_id' => 'required|exists:classrooms,id',
            'section_id' => 'required|exists:sections,id',
            'course_id' => 'required|exists:courses,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
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
