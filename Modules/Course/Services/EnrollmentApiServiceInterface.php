<?php
// Modules/Course/app/Services/EnrollmentApiServiceInterface.php

namespace Modules\Course\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Course\App\Models\Enrollment;

interface EnrollmentApiServiceInterface
{
    public function index(int $perPage = 10): LengthAwarePaginator;
    public function show(int $id): ?Enrollment;
    public function store(array $data): Enrollment;
    public function update(int $id, array $data): Enrollment;
    public function destroy(int $id): bool;
}
