<?php
// Modules/Course/app/Http/Controllers/CourseController.php

namespace Modules\Course\App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Course\Services\CourseApiServiceInterface;
use Modules\Course\App\Http\Request\StoreCourseRequest;
use Modules\Course\App\Http\Request\UpdateCourseRequest;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseApiServiceInterface $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Get all courses with optional pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Always return the full list of courses (no pagination)
            $courseData = $this->courseService->getAllCourses();

            return apiResponse(
                true,
                'Courses retrieved successfully.',
                $courseData
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve courses.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Create a new course
     */
    public function store(StoreCourseRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $course = $this->courseService->createCourse($validated);

            return apiResponse(
                true,
                'Course created successfully.',
                [
                    'uuid' => $course->uuid,
                    'id' => $course->id,
                    'course_name' => $course->course_name,
                    'description' => $course->description,
                    'category' => $course->category,
                    'created_at' => $course->created_at
                ],
                201
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to create course.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Get a specific course by UUID
     */
    public function show(string $uuid): JsonResponse
    {
        try {
            $course = $this->courseService->getCourseByUuid($uuid);

            if (!$course) {
                return apiResponse(
                    false,
                    'Course not found.',
                    null,
                    404
                );
            }

            return apiResponse(
                true,
                'Course retrieved successfully.',
                $course
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve course.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Update a course by UUID
     */
    public function update(UpdateCourseRequest $request, string $uuid): JsonResponse
    {
        try {
            $validated = $request->validated();
            $course = $this->courseService->updateCourse($uuid, $validated);

            return apiResponse(
                true,
                'Course updated successfully.',
                $course
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to update course.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Delete a course by UUID
     */
    public function destroy(string $uuid): JsonResponse
    {
        try {
            $result = $this->courseService->deleteCourse($uuid);

            if ($result) {
                return apiResponse(
                    true,
                    'Course deleted successfully.'
                );
            }

            return apiResponse(
                false,
                'Failed to delete course.',
                null,
                500
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to delete course.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Get course subjects by course UUID
     */
    public function getCourseSubjects(string $uuid): JsonResponse
    {
        try {
            $courseWithSubjects = $this->courseService->getCoursesWithSubjects($uuid);

            return apiResponse(
                true,
                'Course subjects retrieved successfully.',
                $courseWithSubjects
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve course subjects.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Assign subjects to course
     */
    public function assignSubjects(Request $request, string $uuid): JsonResponse
    {
        try {
            $validated = $request->validate([
                'subject_ids' => 'required|array',
                'subject_ids.*' => 'exists:subjects,id'
            ]);

            $result = $this->courseService->assignSubjectsToCourse($uuid, $validated['subject_ids']);

            return apiResponse(
                true,
                'Subjects assigned to course successfully.',
                $result
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to assign subjects to course.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Add single subject to course
     */
    public function addSubject(Request $request, string $uuid): JsonResponse
    {
        try {
            $validated = $request->validate([
                'subject_id' => 'required|exists:subjects,id'
            ]);

            $result = $this->courseService->addSubjectToCourse($uuid, $validated['subject_id']);

            return apiResponse(
                true,
                'Subject added to course successfully.',
                $result
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to add subject to course.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Remove subject from course
     */
    public function removeSubject(string $uuid, int $subjectId): JsonResponse
    {
        try {
            $result = $this->courseService->removeSubjectFromCourse($uuid, $subjectId);

            return apiResponse(
                true,
                'Subject removed from course successfully.',
                $result
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to remove subject from course.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    // Student course methods

    /**
     * Enroll a student in a course
     */
    public function enrollStudent(Request $request, string $uuid): JsonResponse
    {
        try {
            $validated = $request->validate([
                'student_uuid' => 'required|exists:users,uuid'
            ]);

            $result = $this->courseService->enrollStudent($uuid, $validated['student_uuid']);

            return apiResponse(
                true,
                'Student enrolled successfully.',
                $result,
                201
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to enroll student.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Remove a student from a course
     */
    public function removeStudent(string $uuid, string $studentUuid): JsonResponse
    {
        try {
            $result = $this->courseService->removeStudent($uuid, $studentUuid);

            if ($result) {
                return apiResponse(
                    true,
                    'Student removed from course successfully.'
                );
            }

            return apiResponse(
                false,
                'Failed to remove student from course.',
                null,
                500
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to remove student from course.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Update student enrollment status
     */
    public function updateStudentStatus(Request $request, string $uuid, string $studentUuid): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:active,dropped,completed'
            ]);

            $result = $this->courseService->updateStudentStatus($uuid, $studentUuid, $validated['status']);

            return apiResponse(
                true,
                'Student status updated successfully.',
                $result
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to update student status.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Get all students enrolled in a course
     */
    public function getEnrolledStudents(Request $request, string $uuid): JsonResponse
    {
        try {
            $perPage = $request->query('per_page', 10);
            $students = $this->courseService->getEnrolledStudents($uuid, (int)$perPage);

            return apiResponse(
                true,
                'Enrolled students retrieved successfully.',
                $students
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve enrolled students.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Check if student is enrolled in course
     */
    public function isStudentEnrolled(string $uuid, string $studentUuid): JsonResponse
    {
        try {
            $isEnrolled = $this->courseService->isStudentEnrolled($uuid, $studentUuid);

            return apiResponse(
                true,
                'Check completed successfully.',
                ['is_enrolled' => $isEnrolled]
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to check enrollment status.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }
}
