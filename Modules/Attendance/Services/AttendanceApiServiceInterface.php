<?php

namespace Modules\Attendance\Services;

interface AttendanceApiServiceInterface
{
    public function list(array $filters = []);

    public function create(array $data);

    public function getAttendanceById(int $id);

    public function updateAttendance(int $id, array $data);

    public function deleteAttendance(int $id);
}
