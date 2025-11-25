<?php

namespace Modules\Homework\Services\Implementations;

use Modules\Homework\Services\HomeworkApiServiceInterface;
use Modules\Homework\app\Models\Homework;

class HomeworkApiService implements HomeworkApiServiceInterface
{
    public function list(array $filters = [])
    {
        $query = Homework::query();

        if (isset($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }
        
        return $query->with('course')->orderBy('id', 'asc')->get();
    }

    public function create(array $data)
    {
        return Homework::create($data);
    }

    public function getHomeworkById(int $id)
    {
        return Homework::with('course', 'submissions.student')->findOrFail($id);
    }

    public function updateHomework(int $id, array $data)
    {
        $homework = Homework::findOrFail($id);
        $homework->update($data);
        return $homework;
    }

    public function deleteHomework(int $id)
    {
        $homework = Homework::findOrFail($id);
        return $homework->delete();
    }
}
