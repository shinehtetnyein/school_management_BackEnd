<?php

namespace Modules\Attendance\Services\Implementations;

use Modules\Attendance\app\Models\Attendance;
use Modules\Attendance\Services\AttendanceApiServiceInterface;

class AttendanceApiService implements AttendanceApiServiceInterface
{
    public function list(array $filters = [])
    {
        $attendance = Attendance::query()->orderBy('id', 'asc')->get()->map(function($att) {
            return [
                'id' => $att->id,
                'date' => $att->date,
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
}
