<?php

namespace Modules\AcademicYears\Services\Implementations;

use Modules\AcademicYears\app\Models\AcademicYear;
use Modules\AcademicYears\Services\AcademicYearApiServiceInterface;

class AcademicYearApiService implements AcademicYearApiServiceInterface
{
    public function list(array $filters = [])
    {
        $query = AcademicYear::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['is_current'])) {
            $query->where('is_current', $filters['is_current']);
        }

        if (isset($filters['active'])) {
            $query->where('start_date', '<=', now())
                  ->where('end_date', '>=', now());
        }

        return $query->orderBy('start_date', 'desc')->paginate(15);
    }

    public function create(array $data)
    {
        return AcademicYear::create($data);
    }

    public function update(int $id, array $data)
    {
        $academicYear = $this->find($id);
        $academicYear->update($data);
        return $academicYear;
    }

    public function delete(int $id)
    {
        $academicYear = $this->find($id);
        return $academicYear->delete();
    }

    public function find(int $id)
    {
        return AcademicYear::findOrFail($id);
    }

    public function getCurrent()
    {
        return AcademicYear::where('is_current', true)->first();
    }

    public function setCurrent(int $id)
    {
        $academicYear = $this->find($id);

        // First, unset all current flags
        AcademicYear::query()->update(['is_current' => false]);

        // Set the new current year
        $academicYear->update(['is_current' => true]);

        return $academicYear;
    }

    public function getStatistics(int $id)
    {
        $academicYear = $this->find($id);
        return $academicYear->getStatistics();
    }
}
