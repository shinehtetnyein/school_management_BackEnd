<?php

namespace Modules\Results\Services\Implementations;

use Modules\Results\Services\ResultApiServiceInterface;
use Modules\Results\app\Models\Result;

class ResultApiService implements ResultApiServiceInterface
{
    public function getAllResults()
    {
        return Result::with('student', 'exam', 'course')->get();
    }

    public function createResult(array $data)
    {
        return Result::create($data);
    }

    public function getResultById($id)
    {
        return Result::with('student', 'exam', 'course')->findOrFail($id);
    }

    public function updateResult($id, array $data)
    {
        $result = Result::findOrFail($id);
        $result->update($data);
        return $result;
    }

    public function deleteResult($id)
    {
        $result = Result::findOrFail($id);
        $result->delete();
    }
}
