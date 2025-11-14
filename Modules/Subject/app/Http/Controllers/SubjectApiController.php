<?php
// Modules/Subject/app/Http/Controllers/SubjectController.php

namespace Modules\Subject\App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Subject\Services\SubjectApiServiceInterface;
use Modules\Subject\App\Http\Requests\StoreSubjectRequest;
use Modules\Subject\App\Http\Requests\UpdateSubjectRequest;

// If the helper function is in the global namespace, use it with backslash prefix
// Or create the helper function if it doesn't exist

class SubjectApiController extends Controller
{
    protected $subjectService;

    public function __construct(SubjectApiServiceInterface $subjectService){
        $this->subjectService = $subjectService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            // Always return the full list of subjects (no pagination)
            $subjectData = $this->subjectService->getAllSubjects();

            return apiResponse(
                true,
                'Subjects retrieved successfully.',
                $subjectData
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
                $this->transformResponse($subject),
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

    public function show(string $uuid): JsonResponse
    {
        try {
            $subject = $this->subjectService->getSubjectByUuid($uuid);

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
                $this->transformResponse($subject)
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

    public function update(UpdateSubjectRequest $request, string $uuid): JsonResponse
    {
        try {
            $validated = $request->validated();
            $subject = $this->subjectService->updateSubject($uuid, $validated);

            return apiResponse(
                true,
                'Subject updated successfully.',
                $this->transformResponse($subject)
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

    public function destroy(string $uuid): JsonResponse
    {
        try {
            $result = $this->subjectService->deleteSubject($uuid);

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

    public function getByCourse(string $courseUuid): JsonResponse
    {
        try {
            $subjects = $this->subjectService->getSubjectsByCourseUuid($courseUuid);

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

    /**
     * Transform subject model to response format
     */
    private function transformResponse($subject): array
    {
        return [
            'uuid' => $subject->uuid,
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
