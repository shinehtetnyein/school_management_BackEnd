<?php

namespace Modules\Attendance\Services\Implementations;

use Modules\Attendance\app\Models\Attendance;
use Modules\Attendance\Services\AttendanceApiServiceInterface;

class AttendanceApiService implements AttendanceApiServiceInterface
{
    use \Modules\Common\Services\CrudServiceTrait;

    protected string $modelClass = Attendance::class;

    // You may add custom methods specific to Attendance here in future.
}
