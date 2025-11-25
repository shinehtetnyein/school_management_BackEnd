<?php

namespace Modules\Attendance\Services\Implementations;

use Modules\Attendance\app\Models\Attendance;
use Modules\Attendance\Services\AttendanceApiServiceInterface;

class AttendanceApiService implements AttendanceApiServiceInterface
{
    public function list(array $filters = [])
    {
        $query = Attendance::query();

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        if (isset($filters['date'])) {
            $query->whereDate('date', $filters['date']);
        }
        
        $attendance = $query->orderBy('id', 'asc')->get()->map(function($att) {
            return [
                'id' => $att->id,
                'date' => $att->date,
                'status' => $att->status,
                'user_id' => $att->user_id,
                'course_id' => $att->course_id,
                'created_at' => $att->created_at,
                'updated_at' => $att->updated_at,
            ];
        })->toArray();

        return [
            'total_count' => count($attendance),
            'attendance' => $attendance
        ];
    }

    public function create(array $data)
    {
        return Attendance::create($data);
    }

    public function getAttendanceById(int $id)
    {
        return Attendance::find($id);
    }

    public function updateAttendance(int $id, array $data)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->update($data);
        return $attendance;
    }

    public function deleteAttendance(int $id)
    {
        $attendance = Attendance::findOrFail($id);
        return $attendance->delete();
    }
}
