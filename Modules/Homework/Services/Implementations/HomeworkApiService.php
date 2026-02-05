<?php

namespace Modules\Homework\Services\Implementations;

use Modules\Homework\Services\HomeworkApiServiceInterface;
use Modules\Homework\app\Models\Homework;

class HomeworkApiService implements HomeworkApiServiceInterface
{
    use \Modules\Common\Services\CrudServiceTrait;

    protected string $modelClass = Homework::class;
}
