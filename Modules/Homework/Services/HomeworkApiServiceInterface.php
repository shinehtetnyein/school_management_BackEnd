<?php

namespace Modules\Homework\Services;

interface HomeworkApiServiceInterface
{
    public function list(array $filters = []);
    public function create(array $data);
    public function getHomeworkById(int $id);
    public function updateHomework(int $id, array $data);
    public function deleteHomework(int $id);
}
