<?php

namespace Modules\Exams\Services\Implementations;

use Modules\Exams\App\Models\Exam;
use Modules\Exams\Services\ExamApiServiceInterface;
use Modules\Common\Services\CrudServiceTrait;

class ExamApiService implements ExamApiServiceInterface
{
    use CrudServiceTrait;

    protected string $modelClass = Exam::class;

    public function find(int $id)
    {
        return $this->show($id);
    }

    public function getResults(int $id)
    {
        $exam = $this->show($id);
        return $exam->results()->with('student')->get();
    }

    public function submitExam(int $id, array $data)
    {
        $exam = $this->show($id);
        if (method_exists($exam, 'submissions')) {
            return $exam->submissions()->create($data);
        }

        // Fallback: attach to a generic relationship if submissions not defined
        throw new \RuntimeException('Exam submissions relationship not defined on Exam model.');
    }

    public function getSchedule(int $id)
    {
        $exam = $this->show($id);
        return $exam->schedule ?? null;
    }

    public function gradeExam(int $id, array $data)
    {
        $exam = $this->show($id);
        if (method_exists($exam, 'grade')) {
            return $exam->grade($data);
        }

        throw new \RuntimeException('Exam grade method not defined on Exam model.');
    }
}
