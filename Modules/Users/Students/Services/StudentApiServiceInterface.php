<?php

namespace Modules\Users\Students\Services;

interface StudentApiServiceInterface
{
    public function getAllStudents();
    public function createStudent(array $data);
    public function getStudentById($id);
    public function updateStudent($id, array $data);
    public function deleteStudent($id);
}
