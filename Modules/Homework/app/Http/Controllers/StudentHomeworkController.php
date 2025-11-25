<?php

namespace Modules\Homework\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Homework\app\Models\StudentHomework;
use Modules\Homework\app\Http\Resources\StudentHomeworkResource;
use Modules\Homework\app\Http\Requests\StoreStudentHomeworkRequest;

class StudentHomeworkController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentHomework::query();

        if ($request->has('homework_id')) {
            $query->where('homework_id', $request->homework_id);
        }

        if ($request->has('student_id')) {
            $query->where('user_id', $request->student_id);
        }

        return StudentHomeworkResource::collection($query->with('student', 'homework')->get());
    }

    public function store(StoreStudentHomeworkRequest $request)
    {
        $submission = StudentHomework::create($request->validated());
        return new StudentHomeworkResource($submission);
    }

    public function show($id)
    {
        $submission = StudentHomework::with('student', 'homework')->findOrFail($id);
        return new StudentHomeworkResource($submission);
    }

    public function destroy($id)
    {
        $submission = StudentHomework::findOrFail($id);
        $submission->delete();
        return response()->json(null, 204);
    }
}
