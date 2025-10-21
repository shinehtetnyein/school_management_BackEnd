<?php

namespace Modules\Attendance\Services;

interface AttendanceApiServiceInterface
{
    public function list(array $filters = []);

    public function create(array $data);
}
