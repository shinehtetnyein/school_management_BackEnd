<?php

namespace Modules\Exams\Services;

interface ExamApiServiceInterface
{
    public function list(array $filters = []);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function find(int $id);

    // Domain-specific actions
    public function getResults(int $id);

    public function submitExam(int $id, array $data);

    public function getSchedule(int $id);

    public function gradeExam(int $id, array $data);
}
