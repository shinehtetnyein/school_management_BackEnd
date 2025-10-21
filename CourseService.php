<?php

namespace Modules\Courses\Services;

use Modules\Courses\Models\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CourseService
{
    /**
     * Get all courses with pagination.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllCourses()
    {
        return Course::with(['subject', 'academicYear'])->latest()->paginate(15);
    }

    /**
     * Get a single course by its ID.
     *
     * @param int $id
     * @return Course
     */
    public function getCourseById(int $id): Course
    {
        return Course::with(['subject', 'academicYear', 'students', 'teachers'])->findOrFail($id);
    }

    /**
     * Create a new course.
     *
     * @param array $data
     * @return Course
     */
    public function createCourse(array $data): Course
    {
        return Course::create($data);
    }

    /**
     * Update an existing course.
     *
     * @param int $id
     * @param array $data
     * @return Course
     */
    public function updateCourse(int $id, array $data): Course
    {
        $course = $this->getCourseById($id);
        $course->update($data);
        return $course;
    }

    /**
     * Delete a course by its ID.
     *
     * @param int $id
     * @return void
     */
    public function deleteCourse(int $id): void
    {
        $course = $this->getCourseById($id);
        $course->delete();
    }

    /**
     * Enroll students into a course.
     *
     * @param int $courseId
     * @param array $studentIds
     * @return Course
     */
    public function enrollStudents(int $courseId, array $studentIds): Course
    {
        $course = $this->getCourseById($courseId);
        $course->students()->syncWithoutDetaching($studentIds);
        return $course->load('students');
    }
}
