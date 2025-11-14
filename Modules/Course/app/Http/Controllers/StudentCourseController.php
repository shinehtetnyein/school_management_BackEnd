<?php
// Modules/Course/app/Http/Controllers/StudentCourseController.php

namespace Modules\Course\App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Course\Services\StudentCourseApiService;

class StudentCourseController extends Controller
{
    protected $studentCourseService;

    public function __construct(StudentCourseApiService $studentCourseService)
    {
        $this->studentCourseService = $studentCourseService;
    }

    /**
     * Get all courses for a student
     */
    public function index(Request $request, string $studentUuid): JsonResponse
    {
        try {
            $courses = $this->studentCourseService->getStudentCourses($studentUuid);

            return apiResponse(
                true,
                'Student courses retrieved successfully.',
                $courses
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve student courses.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Get active courses for a student
     */
    public function getActive(Request $request, string $studentUuid): JsonResponse
    {
        try {
            $perPage = $request->query('per_page', 10);
            $courses = $this->studentCourseService->getActiveStudentCourses($studentUuid, (int)$perPage);

            return apiResponse(
                true,
                'Active student courses retrieved successfully.',
                $courses
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve active courses.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Get a specific student course
     */
    public function show(string $studentUuid, string $courseUuid): JsonResponse
    {
        try {
            $course = $this->studentCourseService->getStudentCourse($studentUuid, $courseUuid);

            return apiResponse(
                true,
                'Student course retrieved successfully.',
                $course
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve student course.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Get courses by status
     */
    public function getByStatus(Request $request, string $studentUuid): JsonResponse
    {
        try {
            $status = $request->query('status', 'active');
            $perPage = $request->query('per_page', 10);

            $courses = $this->studentCourseService->getStudentCoursesByStatus(
                $studentUuid,
                $status,
                (int)$perPage
            );

            return apiResponse(
                true,
                "Student courses with status '{$status}' retrieved successfully.",
                $courses
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve courses by status.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Get courses by subject
     */
    public function getBySubject(Request $request, string $studentUuid): JsonResponse
    {
        try {
            $subjectId = $request->query('subject_id');
            if (!$subjectId) {
                return apiResponse(
                    false,
                    'Subject ID is required.',
                    null,
                    400
                );
            }

            $perPage = $request->query('per_page', 10);
            $courses = $this->studentCourseService->getStudentCoursesBySubject(
                $studentUuid,
                (int)$subjectId,
                (int)$perPage
            );

            return apiResponse(
                true,
                'Student courses by subject retrieved successfully.',
                $courses
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve courses by subject.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Get enrollment statistics for a student
     */
    public function getStats(string $studentUuid): JsonResponse
    {
        try {
            $stats = $this->studentCourseService->getStudentEnrollmentStats($studentUuid);

            return apiResponse(
                true,
                'Student enrollment statistics retrieved successfully.',
                $stats
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve enrollment statistics.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }
}
