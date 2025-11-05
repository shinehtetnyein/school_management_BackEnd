<?php
// Modules/Subject/app/Http/Controllers/SubjectController.php

namespace Modules\Subject\App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Subject\Services\SubjectApiServiceInterface;
use Modules\Subject\App\Http\Requests\StoreSubjectRequest;
use Modules\Subject\App\Http\Requests\UpdateSubjectRequest;

class SubjectApiController extends Controller
{
    protected $subjectService;

    public function __construct(SubjectApiServiceInterface $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);
            $perPage = $pagPerPage ?? 10;

            if ($noPagination) {
                $subjects = $this->subjectService->getAllSubjects();
            } else {
                $subjects = $this->subjectService->getSubjectsPaginated((int)$perPage);
            }

            return apiResponse(
                true,
                'Subjects retrieved successfully.',
                $subjects
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve subjects.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function store(StoreSubjectRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $subject = $this->subjectService->createSubject($validated);

            return apiResponse(
                true,
                'Subject created successfully.',
                $subject,
                201
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to create subject.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $subject = $this->subjectService->getSubjectById($id);

            if (!$subject) {
                return apiResponse(
                    false,
                    'Subject not found.',
                    null,
                    404
                );
            }

            return apiResponse(
                true,
                'Subject retrieved successfully.',
                $subject
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve subject.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function update(UpdateSubjectRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $subject = $this->subjectService->updateSubject($id, $validated);

            return apiResponse(
                true,
                'Subject updated successfully.',
                $subject
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to update subject.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->subjectService->deleteSubject($id);

            if ($result) {
                return apiResponse(
                    true,
                    'Subject deleted successfully.'
                );
            }

            return apiResponse(
                false,
                'Failed to delete subject.',
                null,
                500
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to delete subject.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function getByCourse(int $courseId): JsonResponse
    {
        try {
            $subjects = $this->subjectService->getSubjectsByCourse($courseId);

            return apiResponse(
                true,
                'Course subjects retrieved successfully.',
                $subjects
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

    public function getByLevel(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'class_level' => 'required|in:beginner,intermediate,advanced'
            ]);

            $subjects = $this->subjectService->getSubjectsByLevel($validated['class_level']);

            return apiResponse(
                true,
                'Subjects by level retrieved successfully.',
                $subjects
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve subjects by level.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function search(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'search' => 'required|string|min:2'
            ]);

            $subjects = $this->subjectService->searchSubjects($validated['search']);

            return apiResponse(
                true,
                'Subjects search completed successfully.',
                $subjects
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to search subjects.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }
}
