<?php

namespace Modules\Exams\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Exams\Models\Exam;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Exam::paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Exam::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Exam::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $exam = Exam::findOrFail($id);
        $exam->update($request->validated());
        return $exam;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Exam::findOrFail($id)->delete();
    }

    /**
     * Get exam results
     */
    public function getResults(string $id)
    {
        $exam = Exam::findOrFail($id);
        return $exam->results()->with('student')->get();
    }

    /**
     * Submit an exam
     */
    public function submitExam(Request $request, string $id)
    {
        $exam = Exam::findOrFail($id);
        return $exam->submissions()->create($request->validated());
    }

    /**
     * Get exam schedule
     */
    public function getSchedule(string $id)
    {
        $exam = Exam::findOrFail($id);
        return $exam->schedule;
    }

    /**
     * Grade an exam submission
     */
    public function gradeExam(Request $request, string $id)
    {
        $exam = Exam::findOrFail($id);
        return $exam->grade($request->validated());
    }
}
