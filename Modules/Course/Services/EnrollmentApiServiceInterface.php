<?php
// Modules/Course/app/Services/EnrollmentApiServiceInterface.php

namespace Modules\Course\Services;

use Modules\Course\App\Models\Enrollment;

interface EnrollmentApiServiceInterface
{
    public function index(int $perPage = 10): array;
    public function show(int $id): ?Enrollment;
    public function store(array $data): Enrollment;
    public function update(int $id, array $data): Enrollment;
    public function destroy(int $id): bool;
}
