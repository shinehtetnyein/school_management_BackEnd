<?php
// Modules/Course/app/Http/Controllers/EnrollmentController.php

namespace Modules\Course\App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Course\Services\EnrollmentApiServiceInterface;
use Modules\Course\App\Http\Requests\StoreEnrollmentRequest;
use Modules\Course\App\Http\Requests\UpdateEnrollmentRequest;

class EnrollmentController extends Controller
{
    protected $enrollmentService;

    public function __construct(EnrollmentApiServiceInterface $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            [$noPagination, $pagPerPage] = \getNoPaginationPagPerPageFromRequest($request);
            $perPage = $pagPerPage ?? 10;

            if ($noPagination) {
                $enrollments = $this->enrollmentService->getAllEnrollments();
            } else {
                $enrollments = $this->enrollmentService->getEnrollmentsPaginated((int)$perPage);
            }

            return \apiResponse(
                true,
                'Enrollments retrieved successfully.',
                $enrollments
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to retrieve enrollments.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function store(StoreEnrollmentRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $enrollment = $this->enrollmentService->createEnrollment($validated);

            return \apiResponse(
                true,
                'Enrollment created successfully.',
                $enrollment,
                201
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to create enrollment.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $enrollment = $this->enrollmentService->getEnrollmentById($id);

            if (!$enrollment) {
                return \apiResponse(
                    false,
                    'Enrollment not found.',
                    null,
                    404
                );
            }

            return \apiResponse(
                true,
                'Enrollment retrieved successfully.',
                $enrollment
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to retrieve enrollment.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function update(UpdateEnrollmentRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $enrollment = $this->enrollmentService->updateEnrollment($id, $validated);

            return \apiResponse(
                true,
                'Enrollment updated successfully.',
                $enrollment
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to update enrollment.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->enrollmentService->deleteEnrollment($id);

            if ($result) {
                return \apiResponse(
                    true,
                    'Enrollment deleted successfully.'
                );
            }

            return \apiResponse(
                false,
                'Failed to delete enrollment.',
                null,
                500
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to delete enrollment.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function getUserEnrollments(int $userId): JsonResponse
    {
        try {
            $enrollments = $this->enrollmentService->getUserEnrollments($userId);

            return \apiResponse(
                true,
                'User enrollments retrieved successfully.',
                $enrollments
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to retrieve user enrollments.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function getCourseEnrollments(int $courseId): JsonResponse
    {
        try {
            $enrollments = $this->enrollmentService->getCourseEnrollments($courseId);

            return \apiResponse(
                true,
                'Course enrollments retrieved successfully.',
                $enrollments
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to retrieve course enrollments.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:active,completed,dropped'
            ]);

            $enrollment = $this->enrollmentService->updateEnrollmentStatus($id, $validated['status']);

            return \apiResponse(
                true,
                'Enrollment status updated successfully.',
                $enrollment
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to update enrollment status.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }
}
