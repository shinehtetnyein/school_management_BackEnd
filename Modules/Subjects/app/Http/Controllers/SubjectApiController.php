<?php

namespace Modules\Subjects\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Subjects\Models\Subject;

class SubjectApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Subject::paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Subject::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Subject::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subject = Subject::findOrFail($id);
        $subject->update($request->validated());
        return $subject;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Subject::findOrFail($id)->delete();
    }

    /**
     * Get Subject results
     */
    public function getResults(string $id)
    {
        $subject = Subject::findOrFail($id);
        return $subject->results()->with('student')->get();
    }

    /**
     * Submit an Subject
     */
    public function submitSubject(Request $request, string $id)
    {
        $subject = Subject::findOrFail($id);
        return $subject->submissions()->create($request->validated());
    }

    /**
     * Get Subject schedule
     */
    public function getSchedule(string $id)
    {
        $subject = Subject::findOrFail($id);
        return $subject->schedule;
    }

    /**
     * Grade an Subject submission
     */
    public function gradeSubject(Request $request, string $id)
    {
        $subject = Subject::findOrFail($id);
        return $subject->grade($request->validated());
    }
}
