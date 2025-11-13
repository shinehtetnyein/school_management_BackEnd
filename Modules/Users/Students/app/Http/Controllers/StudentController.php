<?php

namespace Modules\Users\Students\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Users\Students\Services\StudentApiServiceInterface;
use Modules\Users\Students\app\Http\Request\StudentRequest;
use Modules\Users\Students\app\Http\Resource\StudentResource;

class StudentController extends Controller
{
    protected $service;

    public function __construct(StudentApiServiceInterface $service)
    {
        $this->service = $service;
    }

    // List all students
    public function index()
    {
        $students = $this->service->getAllStudents();
        $totalStudents = \Modules\Users\User\App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'student');
        })->count();

        return response()->json([
            'data' => StudentResource::collection($students),
            'total_students' => $totalStudents
        ]);
    }

    // Store a new student
    public function store(StudentRequest $request)
    {
        $student = $this->service->createStudent($request->validated());
        return new StudentResource($student);
    }

    // Show a student
    public function show($id)
    {
        $student = $this->service->getStudentById($id);
        return new StudentResource($student);
    }

    // Update a student
    public function update(StudentRequest $request, $id)
    {
        $student = $this->service->updateStudent($id, $request->validated());
        return new StudentResource($student);
    }

    // Delete a student
    public function destroy($id)
    {
        $this->service->deleteStudent($id);
        return response()->json(null, 204);
    }
}
