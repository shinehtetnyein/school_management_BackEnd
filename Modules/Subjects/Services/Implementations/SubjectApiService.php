<?php

namespace Modules\Subjects\Services\Implementations;

use Modules\Subjects\Services\SubjectApiServiceInterface;
use Modules\Subjects\Models\Subject;

class SubjectApiService implements SubjectApiServiceInterface
{
    public function list(array $filters = [])
    {
        return Subject::query()->paginate(15);
    }

    public function create(array $data)
    {
        return Subject::create($data);
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
        return Subject::findOrFail($id);
    }
}
