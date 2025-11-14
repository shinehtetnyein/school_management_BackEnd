<?php
// Modules/Course/app/Services/CourseApiServiceInterface.php

namespace Modules\Course\Services;

use Modules\Course\App\Models\Course;

interface CourseApiServiceInterface
{
    public function getAllCourses(): array;

    public function getCourseByUuid(string $uuid): ?Course;

    public function createCourse(array $data): Course;

    public function updateCourse(string $uuid, array $data): Course;

    public function deleteCourse(string $uuid): bool;

    public function getCoursesPaginated(int $perPage = 10): array;

    public function getCoursesWithSubjects(string $courseUuid): array;

    public function assignSubjectsToCourse(string $courseUuid, array $subjectIds): array;

    public function addSubjectToCourse(string $courseUuid, int $subjectId): array;

    public function removeSubjectFromCourse(string $courseUuid, int $subjectId): array;

    // Student course methods
    public function enrollStudent(string $courseUuid, string $studentUuid): array;

    public function removeStudent(string $courseUuid, string $studentUuid): bool;

    public function updateStudentStatus(string $courseUuid, string $studentUuid, string $status): array;

    public function getEnrolledStudents(string $courseUuid, int $perPage = 10): array;

    public function getStudentCourses(string $studentUuid, int $perPage = 10): array;

    public function isStudentEnrolled(string $courseUuid, string $studentUuid): bool;
}
