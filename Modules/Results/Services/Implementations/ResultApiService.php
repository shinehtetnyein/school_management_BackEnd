<?php

namespace Modules\Results\Services\Implementations;

use Modules\Results\Services\ResultApiServiceInterface;
use Modules\Results\app\Models\Result;

class ResultApiService implements ResultApiServiceInterface
{
    use \Modules\Common\Services\CrudServiceTrait;

    protected string $modelClass = Result::class;

    public function getAllResults()
    {
        return $this->list([]);
    }

    public function createResult(array $data)
    {
        return $this->create($data);
    }

    public function getResultById($id)
    {
        return $this->show((int)$id);
    }

    public function updateResult($id, array $data)
    {
        return $this->update((int)$id, $data);
    }

    public function deleteResult($id)
    {
        return $this->delete((int)$id);
    }

    public function find(int $id)
    {
        return $this->show($id);
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

        return $query->orderBy('id', 'asc')->get();
    }
}
