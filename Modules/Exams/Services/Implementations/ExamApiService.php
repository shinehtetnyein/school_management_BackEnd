<?php

namespace Modules\Exams\Services\Implementations;

use Modules\Exams\App\Models\Exam;
use Modules\Exams\Services\ExamApiServiceInterface;

class ExamApiService implements ExamApiServiceInterface
{
    public function list(array $filters = [])
    {
        $exams = Exam::query()->orderBy('id', 'asc')->get()->map(function($exam) {
            return [
                'id' => $exam->id,
                'title' => $exam->title,
                'created_at' => $exam->created_at,
                'updated_at' => $exam->updated_at,
            ];
        })->toArray();

        return [
            'total_count' => count($exams),
            'exams' => $exams
        ];
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
