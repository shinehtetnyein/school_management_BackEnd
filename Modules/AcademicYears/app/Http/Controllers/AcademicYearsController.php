<?php

namespace Modules\AcademicYears\App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\AcademicYears\Services\AcademicYearApiServiceInterface;
use Modules\AcademicYears\App\Http\Requests\StoreAcademicYearRequest;
use Modules\AcademicYears\App\Http\Requests\UpdateAcademicYearRequest;
use Modules\AcademicYears\App\Http\Resources\AcademicYearResource;

class AcademicYearsController extends Controller
{
    protected $academicYearService;

    public function __construct(AcademicYearApiServiceInterface $academicYearService)
    {
        $this->academicYearService = $academicYearService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);

            // Fix: Ensure $pagPerPage is never null and has a default value
            $perPage = $pagPerPage ?? 10;

            if ($noPagination) {
                $academicYears = $this->academicYearService->getAllAcademicYears();
                $data = AcademicYearResource::collection($academicYears);
            } else {
                $academicYears = $this->academicYearService->getAcademicYearsPaginated((int)$perPage);
                $data = AcademicYearResource::collection($academicYears);
            }

            return apiResponse(
                true,
                'Academic years retrieved successfully.',
                $data
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve academic years.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function store(StoreAcademicYearRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $academicYear = $this->academicYearService->createAcademicYear($validated);

            return apiResponse(
                true,
                'Academic year created successfully.',
                new AcademicYearResource($academicYear),
                201
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to create academic year.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $academicYear = $this->academicYearService->getAcademicYearById($id);

            if (!$academicYear) {
                return apiResponse(
                    false,
                    'Academic year not found.',
                    null,
                    404
                );
            }

            return apiResponse(
                true,
                'Academic year retrieved successfully.',
                new AcademicYearResource($academicYear)
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve academic year.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function update(UpdateAcademicYearRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $academicYear = $this->academicYearService->updateAcademicYear($id, $validated);

            return apiResponse(
                true,
                'Academic year updated successfully.',
                new AcademicYearResource($academicYear)
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to update academic year.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->academicYearService->deleteAcademicYear($id);

            if ($result) {
                return apiResponse(
                    true,
                    'Academic year deleted successfully.'
                );
            }

            return apiResponse(
                false,
                'Failed to delete academic year.',
                null,
                500
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to delete academic year.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function setCurrent(int $id): JsonResponse
    {
        try {
            $academicYear = $this->academicYearService->setCurrentAcademicYear($id);

            return apiResponse(
                true,
                'Academic year set as current successfully.',
                new AcademicYearResource($academicYear)
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to set current academic year.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function getCurrent(): JsonResponse
    {
        try {
            $currentAcademicYear = $this->academicYearService->getCurrentAcademicYear();

            if (!$currentAcademicYear) {
                return apiResponse(
                    false,
                    'No current academic year set.',
                    null,
                    404
                );
            }

            return apiResponse(
                true,
                'Current academic year retrieved successfully.',
                new AcademicYearResource($currentAcademicYear)
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve current academic year.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }
}
