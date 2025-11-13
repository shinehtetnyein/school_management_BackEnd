<?php

namespace Modules\Results\Services\Implementations;

use Modules\Results\Services\ResultApiServiceInterface;
use Modules\Results\app\Models\Result;

class ResultApiService implements ResultApiServiceInterface
{
    public function getAllResults()
    {
        return Result::all();
    }

    public function createResult(array $data)
    {
        return Result::create($data);
    }

    public function getResultById($id)
    {
        return Result::findOrFail($id);
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

    public function find(int $id)
    {
        return $this->getResultById($id);
    }

    public function list(array $filters = [])
    {
        $query = Result::query();

        foreach ($filters as $field => $value) {
            if ($value === null) {
                continue;
            }

            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }

        return $query->get();
    }
}
