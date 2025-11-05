<?php
// Modules/Course/app/Services/CourseApiServiceInterface.php

namespace Modules\Course\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Course\App\Models\Course;

interface CourseApiServiceInterface
{
    public function getAllCourses(): array;
    public function getCourseById(int $id): ?Course;
    public function createCourse(array $data): Course;
    public function updateCourse(int $id, array $data): Course;
    public function deleteCourse(int $id): bool;
    public function getCoursesPaginated(int $perPage = 10): LengthAwarePaginator;
    public function getCoursesWithSubjects(int $courseId): array;
    public function assignSubjectsToCourse(int $courseId, array $subjectIds): array;
}
