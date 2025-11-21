<?php

namespace Modules\TimeTable\Services\Implementations;

use Illuminate\Support\Str;
use Modules\TimeTable\app\Models\TimeTable;
use Modules\TimeTable\Services\TimeTableApiServiceInterface;

class TimeTableApiService implements TimeTableApiServiceInterface
{
    /**
     * Get a single timetable entry by ID with optional relations.
     */
    public function get($id, $relations = null)
    {
        $query = TimeTable::query();

        if ($relations) {
            $relations = is_array($relations) ? $relations : [$relations];
            $query->with($relations);
        }

        return $query->find($id);
    }

    /**
     * Get all timetable entries with optional filtering, relations, and pagination.
     */
    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        $query = TimeTable::query();

        // Apply relations
        if ($relations) {
            $relations = is_array($relations) ? $relations : [$relations];
            $query->with($relations);
        }

        // Apply filters
        if ($conds && is_array($conds)) {
            if (isset($conds['classroom_id'])) {
                $query->where('classroom_id', $conds['classroom_id']);
            }
            if (isset($conds['section_id'])) {
                $query->where('section_id', $conds['section_id']);
            }
            if (isset($conds['course_id'])) {
                $query->where('course_id', $conds['course_id']);
            }
            if (isset($conds['day_of_week'])) {
                $query->where('day_of_week', $conds['day_of_week']);
            }
            if (isset($conds['teacher_id'])) {
                $query->where('teacher_id', $conds['teacher_id']);
            }
            if (isset($conds['status'])) {
                $query->where('status', $conds['status']);
            }
        }

        // Handle pagination
        if ($noPagination === true) {
            // Return all results without pagination
            if ($limit) {
                $query->limit($limit);
            }
            if ($offset) {
                $query->offset($offset);
            }
            return $query->get();
        }

        // Apply pagination if specified
        $pagPerPage = $pagPerPage ?? 15;
        return $query->paginate($pagPerPage, ['*'], 'page', request('page', 1));
    }

    /**
     * Create a new timetable entry.
     */
    public function create($data)
    {
        $data['uuid'] = Str::uuid();

        return TimeTable::create($data);
    }

    /**
     * Update an existing timetable entry.
     */
    public function update($id, $data)
    {
        $timeTable = TimeTable::findOrFail($id);
        $timeTable->update($data);

        return $timeTable;
    }

    /**
     * Delete a timetable entry.
     */
    public function delete($id)
    {
        $timeTable = TimeTable::findOrFail($id);
        return $timeTable->delete();
    }

    /**
     * Get timetable by classroom and day.
     */
    public function getByClassroomAndDay($classroomId, $dayOfWeek, $relations = null)
    {
        $query = TimeTable::where('classroom_id', $classroomId)
            ->where('day_of_week', $dayOfWeek);

        if ($relations) {
            $relations = is_array($relations) ? $relations : [$relations];
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get timetable by teacher.
     */
    public function getByTeacher($teacherId, $relations = null)
    {
        $query = TimeTable::where('teacher_id', $teacherId);

        if ($relations) {
            $relations = is_array($relations) ? $relations : [$relations];
            $query->with($relations);
        }

        return $query->get();
    }
}
