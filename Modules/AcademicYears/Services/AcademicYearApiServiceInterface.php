<?php
// Modules/Academic/app/Services/Implementations/AcademicYearApiServiceInterface.php

namespace Modules\AcademicYears\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\AcademicYears\App\Models\AcademicYear;

interface AcademicYearApiServiceInterface
{
    public function getAllAcademicYears(): array;
    public function getAcademicYearById(int $id): ?AcademicYear;
    public function createAcademicYear(array $data): AcademicYear;
    public function updateAcademicYear(int $id, array $data): AcademicYear;
    public function deleteAcademicYear(int $id): bool;
    public function setCurrentAcademicYear(int $id): AcademicYear;
    public function getCurrentAcademicYear(): ?AcademicYear;
    public function getAcademicYearsPaginated(int $perPage = 10): LengthAwarePaginator;
    public function getAcademicYearsWithOptions(array $options = []): mixed;
}
