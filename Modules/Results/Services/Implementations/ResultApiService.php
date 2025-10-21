<?php

namespace Modules\Results\Services\Implementations;

use Modules\Results\Services\ResultApiServiceInterface;
use Modules\Results\Models\Result;

class ResultApiService implements ResultApiServiceInterface
{
    public function list(array $filters = [])
    {
        return Result::query()->paginate(15);
    }

    public function create(array $data)
    {
        return Result::create($data);
    }

    public function update(int $id, array $data)
    {
        $result = $this->find($id);
        $result->update($data);
        return $result;
    }

    public function delete(int $id)
    {
        $result = $this->find($id);
        return $result->delete();
    }

    public function find(int $id)
    {
        return Result::findOrFail($id);
    }
}
