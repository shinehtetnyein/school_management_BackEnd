<?php
// Modules/Course/app/Services/EnrollmentApiServiceInterface.php

namespace Modules\Course\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Course\App\Models\Enrollment;

interface EnrollmentApiServiceInterface
{
    public function getAllEnrollments(): array;
    public function getEnrollmentById(int $id): ?Enrollment;
    public function createEnrollment(array $data): Enrollment;
    public function updateEnrollment(int $id, array $data): Enrollment;
    public function deleteEnrollment(int $id): bool;
    public function getEnrollmentsPaginated(int $perPage = 10): LengthAwarePaginator;
    public function getUserEnrollments(int $userId): array;
    public function getCourseEnrollments(int $courseId): array;
    public function updateEnrollmentStatus(int $id, string $status): Enrollment;
}
