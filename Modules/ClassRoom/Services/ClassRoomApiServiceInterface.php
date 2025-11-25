<?php

namespace Modules\ClassRoom\Services;

interface ClassRoomApiServiceInterface
{
    public function getAllClassRooms();
    public function getClassRoomById(int $id);
    public function createClassRoom(array $data);
    public function updateClassRoom(int $id, array $data);
    public function deleteClassRoom(int $id);
}