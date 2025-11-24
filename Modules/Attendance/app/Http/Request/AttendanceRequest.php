<?php

namespace Modules\Attendance\app\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date' => 'required|date',
            'status' => 'required|in:Present,Absent,Late',
            'verification_method' => 'required|in:Manual,Automatic',
            'user_id' => 'required|integer|exists:users,id',
            'course_id' => 'required|integer|exists:courses,id',
        ];
    }
}
