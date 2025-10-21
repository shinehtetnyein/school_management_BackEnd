<?php

namespace Modules\Attendance\Services\Implementations;

use Modules\Attendance\Services\AttendanceApiServiceInterface;
use Modules\Attendance\Models\Attendance;

class AttendanceApiService implements AttendanceApiServiceInterface
{
    public function list(array $filters = [])
    {
        return Attendance::query()->paginate(15);
    }

    public function create(array $data)
    {
        return Attendance::create($data);
    }
}
