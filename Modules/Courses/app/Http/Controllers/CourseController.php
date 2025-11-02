<?php

namespace Modules\Courses\app\Http\Controllers;

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
   
}
