<?php

namespace Modules\ClassRoom\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ClassRoom\app\Models\Classroom;
use Modules\ClassRoom\app\Http\Resources\ClassroomResource;

class ClassRoomApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load relations and counts to minimize queries
        $classrooms = Classroom::with([
            'sections' => function ($q) {
                $q->withCount('students');
            },
            'timetables' => function ($q) {
                $q->with(['course', 'subject', 'teacher', 'section' => function ($q2) {
                    $q2->withCount('students');
                }]);
            },
        ])->withCount('students')->get();

        return [
            'total_count' => $classrooms->count(),
            'classrooms' => ClassroomResource::collection($classrooms),
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Classroom::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $classroom = Classroom::with([
            'sections' => function ($q) {
                $q->withCount('students');
            },
            'timetables' => function ($q) {
                $q->with(['course', 'subject', 'teacher', 'section' => function ($q2) {
                    $q2->withCount('students');
                }]);
            },
        ])->withCount('students')->findOrFail($id);

        return new ClassroomResource($classroom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Classroom = Classroom::findOrFail($id);
        $Classroom->update($request->validated());
        return $Classroom;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Classroom::findOrFail($id)->delete();
    }

    /**
     * Get Classroom results
     */
    public function getResults(string $id)
    {
        $Classroom = Classroom::findOrFail($id);
        return $Classroom->results()->with('student')->get();
    }

    /**
     * Submit an Classroom
     */
    public function submitClassroom(Request $request, string $id)
    {
        $Classroom = Classroom::findOrFail($id);
        return $Classroom->submissions()->create($request->validated());
    }

    /**
     * Get Classroom schedule
     */
    public function getSchedule(string $id)
    {
        $Classroom = Classroom::findOrFail($id);
        return $Classroom->schedule;
    }

    /**
     * Grade an Classroom submission
     */
    public function gradeClassroom(Request $request, string $id)
    {
        $Classroom = Classroom::findOrFail($id);
        return $Classroom->grade($request->validated());
    }
}
