<?php
// Modules/Course/Services/StudentCourseApiService.php

namespace Modules\Course\Services;

use Modules\Course\App\Models\Course;
use Modules\Users\User\App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentCourseApiService
{
    /**
     * Get all courses for a student
     */
    public function getStudentCourses(string $studentUuid, int $perPage = 10): array
    {
        $student = User::where('uuid', $studentUuid)->first();

        if (!$student) {
            throw new ModelNotFoundException("Student with UUID {$studentUuid} not found.");
        }

        $courses = $student->enrolledCourses()
            ->with(['subjects', 'teachers'])
            ->withCount('students')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($course) {
                return $this->transformCourse($course);
            })->toArray();

        return [
            'total_count' => count($courses),
            'courses' => $courses
        ];
    }

    /**
     * Get active courses for a student
     */
    public function getActiveStudentCourses(string $studentUuid, int $perPage = 10): array
    {
        $student = User::where('uuid', $studentUuid)->first();

        if (!$student) {
            throw new ModelNotFoundException("Student with UUID {$studentUuid} not found.");
        }

        $courses = $student->enrolledCourses()
            ->wherePivot('status', 'active')
            ->with(['subjects', 'teachers'])
            ->withCount('students')
            ->get()
            ->map(function ($course) {
                return $this->transformCourse($course);
            })->toArray();

        return [
            'total_count' => count($courses),
            'courses' => $courses
        ];
    }

    /**
     * Get a specific student course
     */
    public function getStudentCourse(string $studentUuid, string $courseUuid): array
    {
        $student = User::where('uuid', $studentUuid)->first();
        $course = Course::where('uuid', $courseUuid)->first();

        if (!$student) {
            throw new ModelNotFoundException("Student with UUID {$studentUuid} not found.");
        }

        if (!$course) {
            throw new ModelNotFoundException("Course with UUID {$courseUuid} not found.");
        }

        // Check if student is enrolled
        if (!$course->hasStudent($student->id)) {
            throw new \Exception('Student is not enrolled in this course.');
        }

        $enrollment = $student->enrolledCourses()
            ->where('course_id', $course->id)
            ->first();

        return [
            'course' => [
                'uuid' => $course->uuid,
                'id' => $course->id,
                'course_name' => $course->course_name,
                'description' => $course->description,
                'category' => $course->category,
                'subjects_count' => $course->subjects()->count(),
                'students_count' => $course->students()->count()
            ],
            'subjects' => $course->subjects->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'subject_name' => $subject->subject_name,
                    'subject_code' => $subject->subject_code,
                    'class_level' => $subject->class_level
                ];
            })->toArray(),
            'teachers' => $course->teachers->map(function ($teacher) {
                return [
                    'uuid' => $teacher->uuid,
                    'name' => $teacher->name,
                    'email' => $teacher->email
                ];
            })->toArray(),
            'enrollment' => [
                'enrollment_date' => $enrollment->pivot->enrollment_date,
                'status' => $enrollment->pivot->status
            ]
        ];
    }

    /**
     * Get courses by status for a student
     */
    public function getStudentCoursesByStatus(string $studentUuid, string $status, int $perPage = 10): array
    {
        $validStatuses = ['active', 'dropped', 'completed'];

        if (!in_array($status, $validStatuses)) {
            throw new \Exception('Invalid status. Valid statuses: ' . implode(', ', $validStatuses));
        }

        $student = User::where('uuid', $studentUuid)->first();

        if (!$student) {
            throw new ModelNotFoundException("Student with UUID {$studentUuid} not found.");
        }

        $courses = $student->enrolledCourses()
            ->wherePivot('status', $status)
            ->with(['subjects', 'teachers'])
            ->withCount('students')
            ->get()
            ->map(function ($course) {
                return $this->transformCourse($course);
            })->toArray();

        return [
            'total_count' => count($courses),
            'courses' => $courses
        ];
    }

    /**
     * Get courses by subject for a student
     */
    public function getStudentCoursesBySubject(string $studentUuid, int $subjectId, int $perPage = 10): array
    {
        $student = User::where('uuid', $studentUuid)->first();

        if (!$student) {
            throw new ModelNotFoundException("Student with UUID {$studentUuid} not found.");
        }

        $courses = $student->enrolledCourses()
            ->whereHas('subjects', function ($query) use ($subjectId) {
                $query->where('subject_id', $subjectId);
            })
            ->with(['subjects', 'teachers'])
            ->withCount('students')
            ->get()
            ->map(function ($course) {
                return $this->transformCourse($course);
            })->toArray();

        return [
            'total_count' => count($courses),
            'courses' => $courses
        ];
    }

    /**
     * Get student enrollment statistics
     */
    public function getStudentEnrollmentStats(string $studentUuid): array
    {
        $student = User::where('uuid', $studentUuid)->first();

        if (!$student) {
            throw new ModelNotFoundException("Student with UUID {$studentUuid} not found.");
        }

        $activeCourses = $student->enrolledCourses()->wherePivot('status', 'active')->count();
        $completedCourses = $student->enrolledCourses()->wherePivot('status', 'completed')->count();
        $droppedCourses = $student->enrolledCourses()->wherePivot('status', 'dropped')->count();
        $totalCourses = $student->enrolledCourses()->count();

        return [
            'total_courses' => $totalCourses,
            'active_courses' => $activeCourses,
            'completed_courses' => $completedCourses,
            'dropped_courses' => $droppedCourses
        ];
    }

    /**
     * Transform a Course model into an array suitable for API responses
     */
    private function transformCourse(Course $course): array
    {
        return [
            'uuid' => $course->uuid,
            'id' => $course->id,
            'course_name' => $course->course_name,
            'description' => $course->description,
            'category' => $course->category,
            'subjects_count' => $course->subjects_count ?? $course->subjects()->count(),
            'students_count' => $course->students_count ?? $course->students()->count(),
            'created_at' => $course->created_at,
            'updated_at' => $course->updated_at,
        ];
    }
}

