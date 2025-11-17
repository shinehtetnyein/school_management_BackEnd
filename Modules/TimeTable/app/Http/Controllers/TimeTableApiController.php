<?php

namespace Modules\TimeTable\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Course\app\Models\Course as CourseModel;
use Modules\Users\User\App\Models\User as UserModel;

class TimeTableApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('user::show');
    }

    /**
     * Get assigned teachers for a course
     */
    public function getAssignedTeachers($id)
    {
        $course = CourseModel::findOrFail($id);
        $teachers = $course->teachers()->get()->map(function ($t) {
            return [
                'id' => $t->id,
                'uuid' => $t->uuid,
                'name' => $t->name,
                'email' => $t->email,
            ];
        });

        return response()->json(['data' => $teachers]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('user::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Assign a teacher to a course using teacher UUID
     */
    public function assignTeacher(Request $request, $id)
    {
        $validated = $request->validate([
            'teacher_uuid' => 'required|exists:users,uuid',
            'assigned_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive'
        ]);

        $course = CourseModel::findOrFail($id);
        $teacher = UserModel::where('uuid', $validated['teacher_uuid'])->firstOrFail();

        $pivotData = [
            'assigned_date' => $validated['assigned_date'] ?? now(),
            'status' => $validated['status'] ?? 'active'
        ];

        $course->teachers()->syncWithoutDetaching([$teacher->id => $pivotData]);

        return response()->json(['message' => 'Teacher assigned successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
