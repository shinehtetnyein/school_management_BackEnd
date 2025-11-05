<?php
// Modules/Course/app/Services/Implementations/EnrollmentApiService.php

namespace Modules\Course\Services\Implementations;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Course\App\Models\Enrollment;
use Modules\Course\Services\EnrollmentApiServiceInterface;
use Illuminate\Support\Facades\DB;

class EnrollmentApiService implements EnrollmentApiServiceInterface
{
    public function getAllEnrollments(): array
    {
        return Enrollment::with(['course', 'user'])
            ->orderBy('enrolled_at', 'desc')
            ->get()
            ->map(function ($enrollment) {
                return $this->transformEnrollment($enrollment);
            })
            ->toArray();
    }

    public function getEnrollmentById(int $id): ?Enrollment
    {
        return Enrollment::with(['course', 'user'])->find($id);
    }

    public function createEnrollment(array $data): Enrollment
    {
        return DB::transaction(function () use ($data) {
            // Check if enrollment already exists
            $existingEnrollment = Enrollment::where('user_id', $data['user_id'])
                ->where('course_id', $data['course_id'])
                ->first();

            if ($existingEnrollment) {
                throw new \Exception('User is already enrolled in this course.');
            }

            return Enrollment::create($data);
        });
    }

    public function updateEnrollment(int $id, array $data): Enrollment
    {
        return DB::transaction(function () use ($id, $data) {
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->update($data);
            return $enrollment->fresh(['course', 'user']);
        });
    }

    public function deleteEnrollment(int $id): bool
    {
        $enrollment = Enrollment::findOrFail($id);
        return $enrollment->delete();
    }

    public function getEnrollmentsPaginated(int $perPage = 10): LengthAwarePaginator
    {
        $paginator = Enrollment::with(['course', 'user'])
            ->orderBy('enrolled_at', 'desc')
            ->paginate($perPage);

        $paginator->getCollection()->transform(function ($enrollment) {
            return $this->transformEnrollment($enrollment);
        });

        return $paginator;
    }

    public function getUserEnrollments(int $userId): array
    {
        return Enrollment::with(['course'])
            ->where('user_id', $userId)
            ->orderBy('enrolled_at', 'desc')
            ->get()
            ->map(function ($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'course' => [
                        'id' => $enrollment->course->id,
                        'course_name' => $enrollment->course->course_name,
                        'description' => $enrollment->course->description
                    ],
                    'status' => $enrollment->status,
                    'class_level' => $enrollment->class_level,
                    'enrolled_at' => $enrollment->enrolled_at,
                    'updated_at' => $enrollment->updated_at
                ];
            })
            ->toArray();
    }

    public function getCourseEnrollments(int $courseId): array
    {
        return Enrollment::with(['user'])
            ->where('course_id', $courseId)
            ->orderBy('enrolled_at', 'desc')
            ->get()
            ->map(function ($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'user' => [
                        'id' => $enrollment->user->id,
                        'name' => $enrollment->user->name,
                        'email' => $enrollment->user->email
                    ],
                    'status' => $enrollment->status,
                    'class_level' => $enrollment->class_level,
                    'enrolled_at' => $enrollment->enrolled_at,
                    'updated_at' => $enrollment->updated_at
                ];
            })
            ->toArray();
    }

    public function updateEnrollmentStatus(int $id, string $status): Enrollment
    {
        return DB::transaction(function () use ($id, $status) {
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->update(['status' => $status]);
            return $enrollment->fresh(['course', 'user']);
        });
    }

    private function transformEnrollment(Enrollment $enrollment): array
    {
        return [
            'id' => $enrollment->id,
            'user' => [
                'id' => $enrollment->user->id,
                'name' => $enrollment->user->name,
                'email' => $enrollment->user->email
            ],
            'course' => [
                'id' => $enrollment->course->id,
                'course_name' => $enrollment->course->course_name,
                'description' => $enrollment->course->description
            ],
            'status' => $enrollment->status,
            'class_level' => $enrollment->class_level,
            'enrolled_at' => $enrollment->enrolled_at,
            'updated_at' => $enrollment->updated_at
        ];
    }
}
