<?php

namespace Modules\Exams\Services;

interface ExamApiServiceInterface
{
    public function getAllExams();
    public function getExamById(int $id);
    public function createExam(array $data);
    public function updateExam(int $id, array $data);
    public function deleteExam(int $id);
    public function getExamResults(int $id);
}