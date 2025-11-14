<?php
// Modules/Subject/app/Http/Controllers/StudentSubjectController.php

namespace Modules\Subject\App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Subject\Services\SubjectApiServiceInterface;

class StudentSubjectController extends Controller
{
    protected $subjectService;

    public function __construct(SubjectApiServiceInterface $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    /**
     * Get all subjects enrolled by a student
     *
     * @param string $studentUuid
     * @return JsonResponse
     */
    public function getStudentSubjects(string $studentUuid): JsonResponse
    {
        try {
            $subjects = $this->subjectService->getStudentSubjects($studentUuid);

            return apiResponse(
                true,
                'Student subjects retrieved successfully.',
                $subjects
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve student subjects.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Get subject details with all teachers teaching that subject
     *
     * @param string $uuid
     * @return JsonResponse
     */
    public function getSubjectTeachers(string $uuid): JsonResponse
    {
        try {
            $data = $this->subjectService->getSubjectWithTeachers($uuid);

            if (!$data) {
                return apiResponse(
                    false,
                    'Subject not found.',
                    null,
                    404
                );
            }

            return apiResponse(
                true,
                'Subject with teachers retrieved successfully.',
                $data
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve subject teachers.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }
}
