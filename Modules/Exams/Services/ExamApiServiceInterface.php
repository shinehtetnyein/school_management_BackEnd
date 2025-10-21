<?php

namespace Modules\Exams\Services;

interface ExamApiServiceInterface
{
    public function list(array $filters = []);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function find(int $id);
}
