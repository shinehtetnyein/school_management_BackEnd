<?php

namespace Modules\Homework\Services\Implementations;

use Modules\Homework\Services\HomeworkApiServiceInterface;
use Modules\Homework\Models\Homework;

class HomeworkApiService implements HomeworkApiServiceInterface
{
    public function list(array $filters = [])
    {
        $homeworks = Homework::query()->orderBy('id', 'asc')->get()->map(function($hw) {
            return [
                'id' => $hw->id,
                'title' => $hw->title,
                'created_at' => $hw->created_at,
                'updated_at' => $hw->updated_at,
            ];
        })->toArray();

        return [
            'total_count' => count($homeworks),
            'homeworks' => $homeworks
        ];
    }

    public function create(array $data)
    {
        return Homework::create($data);
    }
}
