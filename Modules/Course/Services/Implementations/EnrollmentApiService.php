<?php
// Modules/Course/app/Services/Implementations/EnrollmentApiService.php

namespace Modules\Course\Services\Implementations;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Course\App\Models\Enrollment;
use Modules\Course\Services\EnrollmentApiServiceInterface;
use Illuminate\Support\Facades\DB;

class EnrollmentApiService implements EnrollmentApiServiceInterface
{
    public function index(int $perPage = 10): LengthAwarePaginator
    {
        return Enrollment::with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function show(int $id): ?Enrollment
    {
        return Enrollment::with(['user', 'course'])->find($id);
    }

    public function store(array $data): Enrollment
    {
        return DB::transaction(function () use ($data) {
            // Check if enrollment already exists
            $existingEnrollment = Enrollment::where('user_id', $data['user_id'])
                ->where('course_id', $data['course_id'])
                ->first();

            if ($existingEnrollment) {
                throw new \Exception('Student is already enrolled in this course.');
            }

            // Set default values
            $data['enrolled_at'] = $data['enrolled_at'] ?? now();
            $data['status'] = $data['status'] ?? 'active';

            return Enrollment::create($data);
        });
    }

    public function update(int $id, array $data): Enrollment
    {
        return DB::transaction(function () use ($id, $data) {
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->update($data);
            return $enrollment->fresh(['user', 'course']);
        });
    }

    public function destroy(int $id): bool
    {
        $enrollment = Enrollment::findOrFail($id);
        return $enrollment->delete();
    }
}
