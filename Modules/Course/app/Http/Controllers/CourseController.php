<?php
// Modules/Course/app/Http/Controllers/CourseController.php

namespace Modules\Course\App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Course\Services\CourseApiServiceInterface;
use Modules\Course\App\Http\Requests\StoreCourseRequest;
use Modules\Course\App\Http\Requests\UpdateCourseRequest;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseApiServiceInterface $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);
            $perPage = $pagPerPage ?? 10;

            if ($noPagination) {
                $courses = $this->courseService->getAllCourses();
            } else {
                $courses = $this->courseService->getCoursesPaginated((int)$perPage);
            }

            return apiResponse(
                true,
                'Courses retrieved successfully.',
                $courses
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

    public function store(StoreCourseRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $course = $this->courseService->createCourse($validated);

            return apiResponse(
                true,
                'Course created successfully.',
                $course,
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

    public function show(int $id): JsonResponse
    {
        try {
            $course = $this->courseService->getCourseById($id);

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

    public function update(UpdateCourseRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $course = $this->courseService->updateCourse($id, $validated);

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

    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->courseService->deleteCourse($id);

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

    public function getCourseSubjects(int $id): JsonResponse
    {
        try {
            $courseWithSubjects = $this->courseService->getCoursesWithSubjects($id);

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

    public function assignSubjects(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'subject_ids' => 'required|array',
                'subject_ids.*' => 'exists:subjects,id'
            ]);

            $result = $this->courseService->assignSubjectsToCourse($id, $validated['subject_ids']);

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


}
