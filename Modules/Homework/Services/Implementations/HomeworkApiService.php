<?php

namespace Modules\Homework\Services\Implementations;

use Modules\Homework\Services\HomeworkApiServiceInterface;
use Modules\Homework\Models\Homework;

class HomeworkApiService implements HomeworkApiServiceInterface
{
    public function list(array $filters = [])
    {
        return Homework::query()->paginate(15);
    }

    public function create(array $data)
    {
        return Homework::create($data);
    }
}
