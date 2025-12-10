<?php

namespace Modules\Exams\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Exams\App\Models\Exam;
use Modules\Exams\Services\ExamApiServiceInterface;
use Modules\Exams\app\Http\Request\ExamRequest;
use Modules\Exams\app\Http\Request\SubmitExamRequest;
use Modules\Exams\app\Http\Request\GradeExamRequest;
use Modules\Exams\app\Http\Resource\ExamResource;

class ExamController extends Controller
{
    protected ExamApiServiceInterface $service;

    public function __construct(ExamApiServiceInterface $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $collection = $this->service->list($request->all());
        return ExamResource::collection($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExamRequest $request)
    {
        $exam = $this->service->create($request->validated());
        return new ExamResource($exam);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new ExamResource($this->service->find((int)$id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExamRequest $request, string $id)
    {
        $exam = $this->service->update((int)$id, $request->validated());
        return new ExamResource($exam);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->service->delete((int)$id);
        return response()->json(null, 204);
    }

    /**
     * Get exam results
     */
    public function getResults(string $id)
    {
        return $this->service->getResults((int)$id);
    }

    /**
     * Submit an exam
     */
    public function submitExam(SubmitExamRequest $request, string $id)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('exam_submissions', 'public');
            $data['file'] = $path;
        }

        return $this->service->submitExam((int)$id, $data);
    }

    /**
     * Get exam schedule
     */
    public function getSchedule(string $id)
    {
        return $this->service->getSchedule((int)$id);
    }

    /**
     * Grade an exam submission
     */
    public function gradeExam(GradeExamRequest $request, string $id)
    {
        $data = $request->validated();
        return $this->service->gradeExam((int)$id, $data);
    }
}
