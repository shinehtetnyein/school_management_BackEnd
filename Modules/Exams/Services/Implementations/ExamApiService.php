<?php

namespace Modules\Exams\Services\Implementations;

use Modules\Exams\Services\ExamApiServiceInterface;
use Modules\Exams\Models\Exam;

class ExamApiService implements ExamApiServiceInterface
{
    public function list(array $filters = [])
    {
        return Exam::query()->paginate(15);
    }

    public function create(array $data)
    {
        return Exam::create($data);
    }

    public function update(int $id, array $data)
    {
        $exam = $this->find($id);
        $exam->update($data);
        return $exam;
    }

    public function delete(int $id)
    {
        $exam = $this->find($id);
        return $exam->delete();
    }

    public function find(int $id)
    {
        return Exam::findOrFail($id);
    }
}
