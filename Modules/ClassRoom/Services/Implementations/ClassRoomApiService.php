<?php

namespace Modules\ClassRoom\Services\Implementations;

use Modules\ClassRoom\app\Models\Classroom;
use Modules\ClassRoom\Services\ClassRoomApiServiceInterface;

class ClassRoomApiService implements ClassRoomApiServiceInterface
{
    public function list(array $filters = [])
    {
        $classrooms = Classroom::query()->orderBy('id', 'asc')->get()->map(function($classroom) {
            return [
                'id' => $classroom->id,
                'name' => $classroom->name,
                'created_at' => $classroom->created_at,
                'updated_at' => $classroom->updated_at,
            ];
        })->toArray();

        return [
            'total_count' => count($classrooms),
            'classrooms' => $classrooms
        ];
    }

    public function create(array $data)
    {
        return Classroom::create($data);
    }

    public function update(int $id, array $data)
    {
        $subject = $this->find($id);
        $subject->update($data);
        return $subject;
    }

    public function delete(int $id)
    {
        $subject = $this->find($id);
        return $subject->delete();
    }

    public function find(int $id)
    {
        return Classroom::findOrFail($id);
    }
}
