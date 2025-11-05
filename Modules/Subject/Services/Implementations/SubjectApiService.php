<?php
// Modules/Subject/app/Services/Implementations/SubjectApiService.php

namespace Modules\Subject\Services\Implementations;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Subject\Services\SubjectApiServiceInterface;
use Modules\Course\App\Models\Course;
use Illuminate\Support\Facades\DB;
use Modules\Subject\App\Models\Subject;

class SubjectApiService implements SubjectApiServiceInterface
{
    public function getAllSubjects(): array
    {
        return Subject::orderBy('subject_name', 'asc')
            ->get()
            ->map(function ($subject) {
                return $this->transformSubject($subject);
            })
            ->toArray();
    }

    public function getSubjectById(int $id): ?Subject
    {
        $subject = Subject::find($id);
        return $subject ? $subject : null;
    }

    public function createSubject(array $data): Subject
    {
        return DB::transaction(function () use ($data) {
            return Subject::create($data);
        });
    }

    public function updateSubject(int $id, array $data): Subject
    {
        return DB::transaction(function () use ($id, $data) {
            $subject = Subject::findOrFail($id);
            $subject->update($data);
            return $subject->fresh();
        });
    }

    public function deleteSubject(int $id): bool
    {
        $subject = Subject::findOrFail($id);
        return $subject->delete();
    }

    public function getSubjectsPaginated(int $perPage = 10): LengthAwarePaginator
    {
        $paginator = Subject::orderBy('subject_name', 'asc')->paginate($perPage);

        $paginator->getCollection()->transform(function ($subject) {
            return $this->transformSubject($subject);
        });

        return $paginator;
    }

    public function getSubjectsByCourse(int $courseId): array
    {
        $course = Course::with(['subjects' => function ($query) {
            $query->orderBy('subject_name', 'asc');
        }])->findOrFail($courseId);

        return [
            'course' => [
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
            ->orderBy('subject_name', 'asc')
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
            ->orderBy('subject_name', 'asc')
            ->get()
            ->map(function ($subject) {
                return $this->transformSubject($subject);
            })
            ->toArray();
    }

    private function transformSubject(Subject $subject): array
    {
        return [
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
