<?php

namespace Modules\ClassRoom\Services;

use Modules\ClassRoom\app\Models\ClassRoom;

class ClassRoomApiService implements ClassRoomApiServiceInterface
{
    public function getAllClassRooms()
    {
        return ClassRoom::with([
            'sections' => function ($q) {
                $q->withCount('students');
            },
            'timetables' => function ($q) {
                $q->with(['course', 'subject', 'teacher', 'section' => function ($q2) {
                    $q2->withCount('students');
                }]);
            },
        ])->withCount('students')->get();
    }

    public function getClassRoomById(int $id)
    {
        return ClassRoom::with([
            'sections' => function ($q) {
                $q->withCount('students');
            },
            'timetables' => function ($q) {
                $q->with(['course', 'subject', 'teacher', 'section' => function ($q2) {
                    $q2->withCount('students');
                }]);
            },
        ])->withCount('students')->findOrFail($id);
    }

    public function createClassRoom(array $data)
    {
        return ClassRoom::create($data);
    }

    public function updateClassRoom(int $id, array $data)
    {
        $classRoom = ClassRoom::findOrFail($id);
        $classRoom->update($data);
        return $classRoom;
    }

    public function deleteClassRoom(int $id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        return $classRoom->delete();
    }
}
