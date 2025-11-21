<?php

namespace Modules\ClassRoom\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ClassRoom\app\Models\Classroom;

class ClassRoomApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classrooms = Classroom::get()->map(function($classroom) {
            return [
                'id' => $classroom->id,
                'room_number' => $classroom->room_number ?? null,
                'building' => $classroom->building ?? null,
                'room_type' => $classroom->room_type ?? null,
                'created_at' => $classroom->created_at,
                'updated_at' => $classroom->updated_at,
            ];
        })->toArray();

        return [
            'total_count' => count($classrooms),
            'classrooms' => $classrooms
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
        return Classroom::findOrFail($id);
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
