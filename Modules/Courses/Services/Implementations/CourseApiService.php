<?php

namespace Modules\Users\Student\Services\Implementations;

use App\Enums\Role;
use Modules\Courses\Services\CourseApiServiceInterface;
use Modules\Users\User\Services\UserApiServiceInterface;

class CourseApiService implements CourseApiServiceInterface
{
    public function __construct(protected UserApiServiceInterface $studentApiService) {}

    public function get($id = null, $relations = null, $conds = null)
    {
        $conds['role'] = Role::STUDENT->label();
        return $this->studentApiService->get($id, $relations, $conds);
    }

    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        $conds['role'] = Role::STUDENT->label();
        return $this->studentApiService->getAll($relations, $limit, $offset, $noPagination, $pagPerPage, $conds);
    }

    public function create($courseData)
    {
        $courseData['role'] = Role::STUDENT->value;
        return $this->studentApiService->create($courseData);
    }

    public function update($id, $courseData)
    {
        return $this->studentApiService->update($id, $courseData);
    }

    public function delete($id)
    {
        return $this->studentApiService->delete($id);
    }
}
