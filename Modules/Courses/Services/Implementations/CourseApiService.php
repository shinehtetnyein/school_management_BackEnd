<?php

namespace Modules\Courses\Services\Implementations;

use Modules\Courses\Services\CourseApiServiceInterface;
use Modules\Users\User\Services\UserApiServiceInterface;

class CourseApiService implements CourseApiServiceInterface
{
    public function __construct(protected UserApiServiceInterface $studentApiService) {}

    public function get($id = null, $relations = null, $conds = null)
    {

    }

    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {

    }

    public function create($courseData)
    {

    }

    public function update($id, $courseData)
    {

    }

    public function delete($id)
    {

    }
}
