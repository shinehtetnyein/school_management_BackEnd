<?php

namespace Modules\Homework\Services;

interface HomeworkApiServiceInterface
{
    public function list(array $filters = []);

    public function create(array $data);
}
