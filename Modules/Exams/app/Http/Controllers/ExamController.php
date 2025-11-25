<?php

namespace Modules\Exams\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Exams\Services\ExamApiServiceInterface;
use Modules\Exams\app\Http\Requests\StoreExamRequest;
use Modules\Exams\app\Http\Requests\UpdateExamRequest;
use Modules\Exams\app\Http\Resources\ExamResource;
use Modules\Results\app\Http\Resources\ResultResource;

class ExamController extends Controller
{
    protected ExamApiServiceInterface $examService;

    public function __construct(ExamApiServiceInterface $examService)
    {
        $this->examService = $examService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exams = $this->examService->getAllExams();
        return ExamResource::collection($exams);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExamRequest $request)
    {
        $exam = $this->examService->createExam($request->validated());
        return new ExamResource($exam);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $exam = $this->examService->getExamById($id);
        return new ExamResource($exam);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExamRequest $request, string $id)
    {
        $exam = $this->examService->updateExam($id, $request->validated());
        return new ExamResource($exam);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->examService->deleteExam($id);
        return response()->json(null, 204);
    }

    /**
     * Get exam results
     */
    public function getResults(string $id)
    {
        $results = $this->examService->getExamResults($id);
        return ResultResource::collection($results);
    }
}
