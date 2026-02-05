<?php

namespace Modules\Users\Students\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Users\Students\Services\StudentApiServiceInterface;
use Modules\Users\Students\app\Http\Requests\StoreStudentRequest;
use Modules\Users\Students\app\Http\Requests\UpdateStudentRequest;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentApiServiceInterface $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Get all students
     */
    public function index(): JsonResponse
    {
        try {
            $students = $this->studentService->getAllStudents();
            $totalCount = count($students);

            return apiResponse(
                true,
                'Students retrieved successfully.',
                [
                    'total_count' => $totalCount,
                    'students' => $students->map(function($s) { return $this->transformStudent($s); })->toArray()
                ]
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve students.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Create a new student
     */
    public function store(StoreStudentRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $student = $this->studentService->createStudent($validated);

            return apiResponse(
                true,
                'Student created successfully.',
                $this->transformStudent($student),
                201
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to create student.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Get a specific student by ID
     */
    public function show(int $id): JsonResponse
    {
        try {
            $student = $this->studentService->getStudentById($id);

            if (!$student) {
                return apiResponse(
                    false,
                    'Student not found.',
                    null,
                    404
                );
            }

            return apiResponse(
                true,
                'Student retrieved successfully.',
                $this->transformStudent($student)
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to retrieve student.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Update a student
     */
    public function update(UpdateStudentRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $student = $this->studentService->updateStudent($id, $validated);

            return apiResponse(
                true,
                'Student updated successfully.',
                $this->transformStudent($student)
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to update student.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Delete a student
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->studentService->deleteStudent($id);

            return apiResponse(
                true,
                'Student deleted successfully.'
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to delete student.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Transform student for API response
     */
    private function transformStudent($student): array
    {
        // include related academic data: courses, subjects, classroom, section, results
        $courses = collect($student->enrolledCourses ?? [])->map(function ($c) {
            return [
                'id' => $c->id,
                'uuid' => $c->uuid ?? null,
                'name' => $c->course_name ?? null,
                'code' => $c->code ?? null,
            ];
        })->values()->toArray();

        $subjects = collect($student->subjects ?? [])->map(function ($s) {
            return [
                'id' => $s->id,
                'uuid' => $s->uuid ?? null,
                'name' => $s->subject_name ?? null,
                'code' => $s->code ?? null,
            ];
        })->values()->toArray();

        $classrooms = collect($student->classroom ?? [])->map(function ($c) {
            return [
                'id' => $c->id,
                'room_number' => $c->room_number ?? null,
            ];
        })->values()->toArray();

        $sections = collect($student->section ?? [])->map(function ($s) {
            return [
                'id' => $s->id,
                'name' => $s->name ?? null,
            ];
        })->values()->toArray();

        $results = collect($student->results ?? [])->map(function ($r) {
            return [
                'id' => $r->id,
                'exam_id' => $r->exam_id ?? null,
                'course_id' => $r->course_id ?? null,
                'marks' => $r->marks ?? null,
                'status' => $r->status ?? null,
            ];
        })->values()->toArray();

        // Determine enrollment_date: prefer explicit user field, otherwise derive from first enrolled course pivot
        $enrollmentDate = $student->enrollment_date ?? null;
        if (empty($enrollmentDate) && !empty($student->enrolledCourses) && $student->enrolledCourses->first()) {
            $enrollmentDate = $student->enrolledCourses->first()->pivot->enrollment_date ?? null;
        }

        return [
            'id' => $student->id,
            'uuid' => $student->uuid ?? null,
            'roll_no' => $student->roll_no ?? null,
            'name' => $student->name,
            'email' => $student->email,
            'phone_no' => $student->phone_no,
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'date_of_birth' => $student->date_of_birth,
            'profile_photo' => $student->profile_photo,
            'nrc' => $student->nrc,
            'religion' => $student->religion,
            'mother_tongue' => $student->mother_tongue,
            'language' => $student->language,
            'gender' => $student->gender,
            'status' => $student->status ?? 'active',
            'enrollment_date' => $enrollmentDate,
            'created_at' => $student->created_at,
            'updated_at' => $student->updated_at,
            'courses' => $courses,
            'subjects' => $subjects,
            'classrooms' => $classrooms,
            'sections' => $sections,
            'results' => $results,
        ];
    }
}
