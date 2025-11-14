<?php

namespace Modules\AcademicYears\Services\Implementations;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\AcademicYears\App\Models\AcademicYear;
use Modules\AcademicYears\Services\AcademicYearApiServiceInterface;

class AcademicYearApiService implements AcademicYearApiServiceInterface
{
    public function getAllAcademicYears(): array
    {
        return AcademicYear::select('academic_years.*')
            ->with([
                'createdBy' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'updatedBy' => function ($query) {
                    $query->select('id', 'name', 'email');
                }
            ])
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
    }

    public function getAcademicYearById(int $id): ?AcademicYear
    {
        return AcademicYear::select('academic_years.*')
            ->with([
                'createdBy' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'updatedBy' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'users' => function ($query) {
                    $query->select('users.id', 'users.name', 'users.email');
                }
            ])
            ->find($id);
    }

    public function createAcademicYear(array $data): AcademicYear
    {
        return DB::transaction(function () use ($data) {
            // If setting as current, remove current from others
            if (isset($data['is_current']) && $data['is_current']) {
                AcademicYear::where('is_current', true)->update(['is_current' => false]);
            }

            return AcademicYear::create($data);
        });
    }

    public function updateAcademicYear(int $id, array $data): AcademicYear
    {
        return DB::transaction(function () use ($id, $data) {
            $academicYear = AcademicYear::findOrFail($id);

            // If setting as current, remove current from others
            if (isset($data['is_current']) && $data['is_current']) {
                AcademicYear::where('is_current', true)
                    ->where('id', '!=', $id)
                    ->update(['is_current' => false]);
            }

            $academicYear->update($data);

            return $academicYear->fresh(['createdBy:id,name,email', 'updatedBy:id,name,email']);
        });
    }

    public function deleteAcademicYear(int $id): bool
    {
        $academicYear = AcademicYear::findOrFail($id);

        // Prevent deletion of current academic year
        if ($academicYear->is_current) {
            throw new \Exception('Cannot delete the current academic year.');
        }

        return $academicYear->delete();
    }

    public function setCurrentAcademicYear(int $id): AcademicYear
    {
        return DB::transaction(function () use ($id) {
            // Remove current from all academic years
            AcademicYear::where('is_current', true)->update(['is_current' => false]);

            // Set the selected one as current
            $academicYear = AcademicYear::findOrFail($id);
            $academicYear->update(['is_current' => true]);

            return $academicYear->fresh(['createdBy:id,name,email', 'updatedBy:id,name,email']);
        });
    }

    public function getCurrentAcademicYear(): ?AcademicYear
    {
        return AcademicYear::select('academic_years.*')
            ->with([
                'createdBy' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'updatedBy' => function ($query) {
                    $query->select('id', 'name', 'email');
                }
            ])
            ->current()
            ->first();
    }

    public function getAcademicYearsPaginated(int $perPage = 10): array
    {
        $years = AcademicYear::select('academic_years.*')
            ->with([
                'createdBy' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'updatedBy' => function ($query) {
                    $query->select('id', 'name', 'email');
                }
            ])
            ->orderBy('start_date', 'desc')
            ->get()
            ->map(function($year) {
                return [
                    'id' => $year->id,
                    'name' => $year->name,
                    'start_date' => $year->start_date,
                    'end_date' => $year->end_date,
                    'is_current' => $year->is_current,
                ];
            })->toArray();

        return [
            'total_count' => count($years),
            'academic_years' => $years
        ];
    }

    public function getAcademicYearsWithOptions(array $options = []): mixed
    {
        $query = AcademicYear::select('academic_years.*')
            ->with([
                'createdBy' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'updatedBy' => function ($query) {
                    $query->select('id', 'name', 'email');
                }
            ])
            ->orderBy('start_date', 'desc');

        $years = $query->get()
            ->map(function($year) {
                return [
                    'id' => $year->id,
                    'name' => $year->name,
                    'start_date' => $year->start_date,
                    'end_date' => $year->end_date,
                    'is_current' => $year->is_current,
                ];
            })->toArray();

        return [
            'total_count' => count($years),
            'academic_years' => $years
        ];
    }
}
