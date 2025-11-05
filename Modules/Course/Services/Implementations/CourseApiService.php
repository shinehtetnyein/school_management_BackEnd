<?php
// Modules/Course/app/Services/Implementations/CourseApiService.php

namespace Modules\Course\Services\Implementations;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Course\Services\CourseApiServiceInterface;
use Illuminate\Support\Facades\DB;
use Modules\Course\App\Models\Course;
use Modules\Subject\App\Models\Subject;

class CourseApiService implements CourseApiServiceInterface
{
    public function getAllCourses(): array
    {
        return Course::withCount('subjects')
            ->orderBy('course_name', 'asc')
            ->get()
            ->map(function ($course) {
                return $this->transformCourse($course);
            })
            ->toArray();
    }

    public function getCourseById(int $id): ?Course
    {
        $course = Course::withCount('subjects')->find($id);
        return $course ? $course : null;
    }

    public function createCourse(array $data): Course
    {
        return DB::transaction(function () use ($data) {
            return Course::create($data);
        });
    }

    public function updateCourse(int $id, array $data): Course
    {
        return DB::transaction(function () use ($id, $data) {
            $course = Course::findOrFail($id);
            $course->update($data);
            return $course->fresh();
        });
    }

    public function deleteCourse(int $id): bool
    {
        $course = Course::findOrFail($id);
        return $course->delete();
    }

    public function getCoursesPaginated(int $perPage = 10): LengthAwarePaginator
    {
        $paginator = Course::withCount('subjects')
            ->orderBy('course_name', 'asc')
            ->paginate($perPage);

        $paginator->getCollection()->transform(function ($course) {
            return $this->transformCourse($course);
        });

        return $paginator;
    }

    public function getCoursesWithSubjects(int $courseId): array
    {
        $course = Course::with(['subjects' => function ($query) {
            $query->select('subjects.id', 'subjects.subject_name', 'subjects.subject_code', 'subjects.class_level')
                  ->orderBy('subject_name', 'asc');
        }])->withCount('subjects')->findOrFail($courseId);

        return [
            'course' => $this->transformCourse($course),
            'subjects' => $course->subjects->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'subject_name' => $subject->subject_name,
                    'subject_code' => $subject->subject_code,
                    'class_level' => $subject->class_level,
                    'assigned_at' => $subject->pivot->created_at ?? null
                ];
            })->toArray()
        ];
    }

    public function assignSubjectsToCourse(int $courseId, array $subjectIds): array
    {
        return DB::transaction(function () use ($courseId, $subjectIds) {
            $course = Course::findOrFail($courseId);

            // Validate that all subject IDs exist
            $existingSubjects = Subject::whereIn('id', $subjectIds)->pluck('id')->toArray();
            $nonExistingSubjects = array_diff($subjectIds, $existingSubjects);

            if (!empty($nonExistingSubjects)) {
                throw new \Exception('One or more subject IDs do not exist: ' . implode(', ', $nonExistingSubjects));
            }

            // Sync subjects (this will replace existing subjects)
            $course->subjects()->sync($subjectIds);

            return $this->getCoursesWithSubjects($courseId);
        });
    }

    public function addSubjectToCourse(int $courseId, int $subjectId): array
    {
        return DB::transaction(function () use ($courseId, $subjectId) {
            $course = Course::findOrFail($courseId);
            $subject = Subject::findOrFail($subjectId);

            if ($course->hasSubject($subjectId)) {
                throw new \Exception('Subject is already assigned to this course.');
            }

            $course->subjects()->attach($subjectId);

            return $this->getCoursesWithSubjects($courseId);
        });
    }

    public function removeSubjectFromCourse(int $courseId, int $subjectId): array
    {
        return DB::transaction(function () use ($courseId, $subjectId) {
            $course = Course::findOrFail($courseId);

            if (!$course->hasSubject($subjectId)) {
                throw new \Exception('Subject is not assigned to this course.');
            }

            $course->subjects()->detach($subjectId);

            return $this->getCoursesWithSubjects($courseId);
        });
    }

    private function transformCourse(Course $course): array
    {
        return [
            'id' => $course->id,
            'course_name' => $course->course_name,
            'description' => $course->description,
            'category' => $course->category,
            'subjects_count' => $course->subjects_count,
            'created_at' => $course->created_at,
            'updated_at' => $course->updated_at,
        ];
    }
}
