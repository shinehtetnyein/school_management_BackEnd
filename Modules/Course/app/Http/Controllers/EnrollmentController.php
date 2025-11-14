<?php
// Modules/Course/app/Http/Controllers/EnrollmentController.php

namespace Modules\Course\App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Course\Services\EnrollmentApiServiceInterface;
use Modules\Course\App\Http\Request\StoreEnrollmentRequest;
use Modules\Course\App\Http\Request\UpdateEnrollmentRequest;

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
            $enrollments = $this->enrollmentService->index();

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
            $enrollment = $this->enrollmentService->store($validated);

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
            $enrollment = $this->enrollmentService->show($id);

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
            $enrollment = $this->enrollmentService->update($id, $validated);

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
            $result = $this->enrollmentService->destroy($id);

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
            return \apiResponse(
                false,
                'Failed to delete enrollment.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }
}
