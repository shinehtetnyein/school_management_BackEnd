<?php

namespace Modules\Courses\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Courses\Http\Requests\StoreCourseRequest;
use Modules\Courses\Http\Requests\UpdateCourseRequest;
use Modules\Courses\Http\Requests\EnrollStudentRequest;
use Modules\Courses\Http\Resources\CourseResource;
use Modules\Courses\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CourseController extends Controller
{
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $courses = $this->courseService->getAllCourses();
        return CourseResource::collection($courses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request): JsonResponse
    {
        $course = $this->courseService->createCourse($request->validated());
        return response()->json(new CourseResource($course), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): CourseResource
    {
        $course = $this->courseService->getCourseById($id);
        return new CourseResource($course);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, int $id): CourseResource
    {
        $course = $this->courseService->updateCourse($id, $request->validated());
        return new CourseResource($course);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->courseService->deleteCourse($id);
        return response()->json(null, 204);
    }

    /**
     * Enroll students in a specific course.
     */
    public function enrollStudents(EnrollStudentRequest $request, int $id): CourseResource
    {
        $course = $this->courseService->enrollStudents($id, $request->validated()['student_ids']);
        return new CourseResource($course);
    }
}
