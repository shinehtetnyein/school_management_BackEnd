<?php

namespace Modules\Exams\Services;

use Modules\Exams\app\Models\Exam;

class ExamApiService implements ExamApiServiceInterface
{
    public function getAllExams()
    {
        return Exam::with('course')->get();
    }

    public function getExamById(int $id)
    {
        return Exam::with('course', 'results.student')->findOrFail($id);
    }

    public function createExam(array $data)
    {
        return Exam::create($data);
    }

    public function updateExam(int $id, array $data)
    {
        $exam = Exam::findOrFail($id);
        $exam->update($data);
        return $exam;
    }

    public function deleteExam(int $id)
    {
        $exam = Exam::findOrFail($id);
        return $exam->delete();
    }

    public function getExamResults(int $id)
    {
        $exam = Exam::findOrFail($id);
        return $exam->results()->with('student')->get();
    }
}
