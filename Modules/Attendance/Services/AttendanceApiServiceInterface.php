<?php

namespace Modules\Attendance\Services;

interface AttendanceApiServiceInterface
{
    public function list(array $filters = []);

    public function create(array $data);

    public function show(int $id);

    public function update(int $id, array $data);

    public function delete(int $id);
}
