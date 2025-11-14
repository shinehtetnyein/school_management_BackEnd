<?php
// Modules/Subject/app/Services/SubjectApiServiceInterface.php

namespace Modules\Subject\Services;

use Modules\Subject\App\Models\Subject;

interface SubjectApiServiceInterface
{
    /**
     * Get all subjects
     */
    public function getAllSubjects(): array;

    /**
     * Get subject by UUID
     */
    public function getSubjectByUuid(string $uuid): ?Subject;

    /**
     * Create a new subject
     */
    public function createSubject(array $data): Subject;

    /**
     * Update subject by UUID
     */
    public function updateSubject(string $uuid, array $data): Subject;

    /**
     * Delete subject by UUID
     */
    public function deleteSubject(string $uuid): bool;

    /**
     * Get subjects with pagination
     */
    public function getSubjectsPaginated(int $perPage = 10): array;

    /**
     * Get subjects by course UUID
     */
    public function getSubjectsByCourseUuid(string $courseUuid): array;

    /**
     * Get subjects by class level
     */
    public function getSubjectsByLevel(string $classLevel): array;

    /**
     * Search subjects
     */
    public function searchSubjects(string $searchTerm): array;

    /**
     * Get subjects for a student (enrolled courses' subjects)
     */
    public function getStudentSubjects(string $studentUuid): array;

    /**
     * Get subject details with teachers info
     */
    public function getSubjectWithTeachers(string $uuid): ?array;
}
