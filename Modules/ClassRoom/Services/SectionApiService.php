<?php

namespace Modules\ClassRoom\Services;

use Modules\ClassRoom\app\Models\Section;

class SectionApiService implements SectionApiServiceInterface
{
    public function getAllSections()
    {
        return Section::with('classroom', 'students')->get();
    }

    public function getSectionById(int $id)
    {
        return Section::with('classroom', 'students')->findOrFail($id);
    }

    public function createSection(array $data)
    {
        return Section::create($data);
    }

    public function updateSection(int $id, array $data)
    {
        $section = Section::findOrFail($id);
        $section->update($data);
        return $section;
    }

    public function deleteSection(int $id)
    {
        $section = Section::findOrFail($id);
        return $section->delete();
    }
}
