<?php

namespace Modules\Users\Teachers\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Users\Teachers\Services\TeacherApiServiceInterface;
use Modules\Users\Teachers\app\Http\Resources\TeacherResource;
use Modules\Users\Teachers\app\Http\Requests\StoreTeacherRequest;
use Modules\Users\Teachers\app\Http\Requests\UpdateTeacherRequest;
use Modules\Users\Teachers\app\Http\Requests\AssignSubjectRequest;
use Modules\Users\Teachers\app\Http\Requests\AssignCourseRequest;

class TeacherController extends Controller
{
    protected $teacherService;

    public function __construct(TeacherApiServiceInterface $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * Get all teachers with pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $result = $this->teacherService->index();

            // Service may return a wrapper with pagination/meta, or a collection directly.
            if (is_array($result) && array_key_exists('teachers', $result)) {
                $teachers = $result['teachers'];
                $meta = $result;
            } else {
                $teachers = $result;
                $meta = null;
            }

            $response = [
                'success' => true,
                'message' => 'Teachers retrieved successfully',
                'data' => TeacherResource::collection($teachers),
            ];

            if ($meta) {
                $response['meta'] = $meta;
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve teachers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific teacher
     */
    public function show(string $uuid): JsonResponse
    {
        try {
            $teacher = $this->teacherService->show($uuid);

            return response()->json([
                'success' => true,
                'message' => 'Teacher retrieved successfully',
                'data' => new TeacherResource($teacher)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create a new teacher
     */
    public function store(StoreTeacherRequest $request): JsonResponse
    {
        try {
            $teacher = $this->teacherService->store($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Teacher created successfully',
                'data' => new TeacherResource($teacher)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create teacher',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a teacher
     */
    public function update(UpdateTeacherRequest $request, string $uuid): JsonResponse
    {
        try {
            $teacher = $this->teacherService->update($uuid, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Teacher updated successfully',
                'data' => new TeacherResource($teacher)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update teacher',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a teacher
     */
    public function destroy(string $uuid): JsonResponse
    {
        try {
            $this->teacherService->destroy($uuid);

            return response()->json([
                'success' => true,
                'message' => 'Teacher deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete teacher',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get teachers by department
     */
    public function getByDepartment(int $departmentId): JsonResponse
    {
        try {
            $teachers = $this->teacherService->getByDepartment($departmentId);

            return response()->json([
                'success' => true,
                'message' => 'Teachers retrieved successfully',
                'data' => TeacherResource::collection($teachers)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve teachers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get teachers for a specific subject
     */
    public function getTeachersForSubject(int $subjectId): JsonResponse
    {
        try {
            $teachers = $this->teacherService->getTeachersForSubject($subjectId);

            return response()->json([
                'success' => true,
                'message' => 'Teachers retrieved successfully',
                'data' => TeacherResource::collection($teachers)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve teachers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get teacher's assigned courses
     */
    public function getTeacherCourses(string $uuid): JsonResponse
    {
        try {
            $courses = $this->teacherService->getTeacherCourses($uuid);

            return response()->json([
                'success' => true,
                'message' => 'Teacher courses retrieved successfully',
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get teacher's assigned subjects
     */
    public function getTeacherSubjects(string $uuid): JsonResponse
    {
        try {
            $subjects = $this->teacherService->getTeacherSubjects($uuid);

            return response()->json([
                'success' => true,
                'message' => 'Teacher subjects retrieved successfully',
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subjects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign subject to teacher
     */
    public function assignSubject(AssignSubjectRequest $request, string $uuid): JsonResponse
    {
        try {
            $subjects = $this->teacherService->assignSubject($uuid, $request->subject_id);

            return response()->json([
                'success' => true,
                'message' => 'Subject assigned successfully',
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove subject from teacher
     */
    public function removeSubject(Request $request, string $uuid, int $subjectId): JsonResponse
    {
        try {
            $this->teacherService->removeSubject($uuid, $subjectId);

            return response()->json([
                'success' => true,
                'message' => 'Subject removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign course to teacher
     */
    public function assignCourse(AssignCourseRequest $request, string $uuid): JsonResponse
    {
        try {
            $courses = $this->teacherService->assignCourse($uuid, $request->course_id);

            return response()->json([
                'success' => true,
                'message' => 'Course assigned successfully',
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove course from teacher
     */
    public function removeCourse(Request $request, string $uuid, int $courseId): JsonResponse
    {
        try {
            $this->teacherService->removeCourse($uuid, $courseId);

            return response()->json([
                'success' => true,
                'message' => 'Course removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove course',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
