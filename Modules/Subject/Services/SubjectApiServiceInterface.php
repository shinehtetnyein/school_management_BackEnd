<?php
// Modules/Subject/app/Services/SubjectApiServiceInterface.php

namespace Modules\Subject\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Subject\App\Models\Subject;

interface SubjectApiServiceInterface
{
    public function getAllSubjects(): array;
    public function getSubjectById(int $id): ?Subject;
    public function createSubject(array $data): Subject;
    public function updateSubject(int $id, array $data): Subject;
    public function deleteSubject(int $id): bool;
    public function getSubjectsPaginated(int $perPage = 10): LengthAwarePaginator;
    public function getSubjectsByCourse(int $courseId): array;
    public function getSubjectsByLevel(string $classLevel): array;
    public function searchSubjects(string $searchTerm): array;
}
