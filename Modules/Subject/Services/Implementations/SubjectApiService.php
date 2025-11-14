<?php
// Modules/Subject/app/Services/Implementations/SubjectApiService.php

namespace Modules\Subject\Services\Implementations;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Subject\Services\SubjectApiServiceInterface;
use Modules\Course\App\Models\Course;
use Modules\Subject\App\Models\Subject;
use Modules\Users\User\App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubjectApiService implements SubjectApiServiceInterface
{
    public function getAllSubjects(): array
    {
        $subjects = Subject::orderBy('id', 'asc')
            ->get()
            ->map(function ($subject) {
                return $this->transformSubject($subject);
            })
            ->toArray();

        return [
            'total_count' => count($subjects),
            'subjects' => $subjects
        ];
    }

    public function getSubjectByUuid(string $uuid): ?Subject
    {
        $subject = Subject::where('uuid', $uuid)->first();
        return $subject ? $subject : null;
    }

    public function createSubject(array $data): Subject
    {
        return DB::transaction(function () use ($data) {
            if (!isset($data['uuid'])) {
                $data['uuid'] = (string) Str::uuid();
            }
            return Subject::create($data);
        });
    }

    public function updateSubject(string $uuid, array $data): Subject
    {
        return DB::transaction(function () use ($uuid, $data) {
            $subject = Subject::where('uuid', $uuid)->firstOrFail();
            $subject->update($data);
            return $subject->fresh();
        });
    }

    public function deleteSubject(string $uuid): bool
    {
        $subject = Subject::where('uuid', $uuid)->firstOrFail();
        return $subject->delete();
    }

    public function getSubjectsPaginated(int $perPage = 10): array
    {
        $subjects = Subject::orderBy('id', 'asc')
            ->get()
            ->map(function ($subject) {
                return $this->transformSubject($subject);
            })->toArray();

        return [
            'total_count' => count($subjects),
            'subjects' => $subjects
        ];
    }

    public function getSubjectsByCourseUuid(string $courseUuid): array
    {
        $course = Course::where('uuid', $courseUuid)
            ->with(['subjects' => function ($query) {
            $query->orderBy('id', 'asc');
        }])
            ->firstOrFail();

        return [
            'course' => [
                'uuid' => $course->uuid,
                'id' => $course->id,
                'course_name' => $course->course_name
            ],
            'subjects' => $course->subjects->map(function ($subject) {
                return $this->transformSubject($subject);
            })->toArray()
        ];
    }

    public function getSubjectsByLevel(string $classLevel): array
    {
        return Subject::where('class_level', $classLevel)
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($subject) {
                return $this->transformSubject($subject);
            })
            ->toArray();
    }

    public function searchSubjects(string $searchTerm): array
    {
        return Subject::where('subject_name', 'like', "%{$searchTerm}%")
            ->orWhere('subject_code', 'like', "%{$searchTerm}%")
            ->orWhere('subject_desc', 'like', "%{$searchTerm}%")
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($subject) {
                return $this->transformSubject($subject);
            })
            ->toArray();
    }

    public function getStudentSubjects(string $studentUuid): array
    {
        $student = User::where('uuid', $studentUuid)->firstOrFail();

        // Get subjects through enrolled courses
        $subjects = Subject::whereHas('courses', function ($query) use ($student) {
            $query->whereHas('students', function ($q) use ($student) {
                $q->where('user_id', $student->id);
            });
        })
        ->with(['courses' => function ($query) use ($student) {
            $query->whereHas('students', function ($q) use ($student) {
                $q->where('user_id', $student->id);
            });
        }])
        ->orderBy('id', 'asc')
        ->get();

        return [
            'student_uuid' => $studentUuid,
            'subjects_count' => $subjects->count(),
            'subjects' => $subjects->map(function ($subject) {
                return $this->transformSubject($subject);
            })->toArray()
        ];
    }

    public function getSubjectWithTeachers(string $uuid): ?array
    {
        $subject = Subject::where('uuid', $uuid)
            ->with(['courses' => function ($query) {
                $query->with('teachers')->orderBy('id', 'asc');
            }])
            ->first();

        if (!$subject) {
            return null;
        }

        $teachers = collect();
        foreach ($subject->courses as $course) {
            $teachers = $teachers->merge($course->teachers);
        }

        return [
            'subject' => $this->transformSubject($subject),
            'courses_count' => $subject->courses->count(),
            'teachers_count' => $teachers->unique('id')->count(),
            'teachers' => $teachers->unique('id')->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'uuid' => $teacher->uuid ?? null,
                    'first_name' => $teacher->first_name,
                    'last_name' => $teacher->last_name,
                    'email' => $teacher->email
                ];
            })->values()->toArray()
        ];
    }

    /**
     * Transform subject for API response
     */
    private function transformSubject(Subject $subject): array
    {
        return [
            'uuid' => $subject->uuid,
            'id' => $subject->id,
            'subject_code' => $subject->subject_code,
            'subject_name' => $subject->subject_name,
            'subject_desc' => $subject->subject_desc,
            'class_level' => $subject->class_level,
            'status' => $subject->status,
            'created_at' => $subject->created_at,
            'updated_at' => $subject->updated_at,
        ];
    }
}
