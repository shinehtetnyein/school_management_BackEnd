<?php

namespace Modules\TimeTable\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Modules\TimeTable\Services\TimeTableAuthorizationServiceInterface;
use Modules\TimeTable\Services\Implementations\TimeTableAuthorizationService as DefaultTimeTableAuthorizationService;

class UpdateTimeTableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Try FormRequest user first, fallback to the global auth user.
        $user = $this->user() ?? auth()->user();

        if (app()->bound(TimeTableAuthorizationServiceInterface::class)) {
            $service = app(TimeTableAuthorizationServiceInterface::class);
        } else {
            $service = new DefaultTimeTableAuthorizationService();
        }

        // Diagnostic logging to help debug authorization issues.
        try {
            $userId = optional($user)->id;
            $hasRoleMethod = is_object($user) && method_exists($user, 'hasRole');
            $roleCheck = $hasRoleMethod ? ($user->hasRole(['root_admin', 'admin', 'teacher']) ? 'yes' : 'no') : 'no_hasRole_method';
            Log::debug('TimeTable Update authorize check', [
                'this_user_id' => $userId,
                'auth_check' => auth()->check(),
                'guard' => auth()->getDefaultDriver(),
                'hasRole_method' => $hasRoleMethod,
                'role_check_result' => $roleCheck,
            ]);
        } catch (\Throwable $e) {
            Log::error('TimeTable Update authorize logging failed: '.$e->getMessage());
        }

        return $service->authorize($user);
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
