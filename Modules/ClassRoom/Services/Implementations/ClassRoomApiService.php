<?php

namespace Modules\ClassRoom\Services\Implementations;

use Modules\ClassRoom\app\Models\Classroom;
use Modules\ClassRoom\Services\ClassRoomApiServiceInterface;

class ClassRoomApiService implements ClassRoomApiServiceInterface
{
    public function list(array $filters = [])
    {
        return Classroom::query()->paginate(15);
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
