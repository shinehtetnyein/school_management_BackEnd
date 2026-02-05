<?php

namespace Modules\ClassRoom\Services;

interface SectionApiServiceInterface
{
    public function getAllSections();
    public function getSectionById(int $id);
    public function createSection(array $data);
    public function updateSection(int $id, array $data);
    public function deleteSection(int $id);
}
