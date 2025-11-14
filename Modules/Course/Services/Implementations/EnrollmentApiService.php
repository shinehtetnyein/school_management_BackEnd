<?php
// Modules/Course/app/Services/Implementations/EnrollmentApiService.php

namespace Modules\Course\Services\Implementations;

use Modules\Course\App\Models\Enrollment;
use Modules\Course\Services\EnrollmentApiServiceInterface;
use Illuminate\Support\Facades\DB;

class EnrollmentApiService implements EnrollmentApiServiceInterface
{
    public function index(int $perPage = 10): array
    {
        $enrollments = Enrollment::with(['user', 'course'])
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'user' => [
                        'id' => $enrollment->user->id ?? null,
                        'uuid' => $enrollment->user->uuid ?? null,
                        'name' => $enrollment->user->name ?? null,
                        'email' => $enrollment->user->email ?? null,
                    ],
                    'course' => [
                        'id' => $enrollment->course->id ?? null,
                        'uuid' => $enrollment->course->uuid ?? null,
                        'course_name' => $enrollment->course->course_name ?? null,
                    ],
                    'enrolled_at' => $enrollment->enrolled_at ?? $enrollment->created_at,
                    'status' => $enrollment->status,
                ];
            })->toArray();

        return [
            'total_count' => count($enrollments),
            'enrollments' => $enrollments
        ];
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
