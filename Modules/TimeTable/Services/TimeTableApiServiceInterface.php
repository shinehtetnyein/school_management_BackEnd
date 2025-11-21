<?php

namespace Modules\TimeTable\Services;

interface TimeTableApiServiceInterface
{
    /**
     * Get a single timetable entry by ID with optional relations.
     *
     * @param int $id
     * @param string|array|null $relations
     * @return \Modules\TimeTable\app\Models\TimeTable|null
     */
    public function get($id, $relations = null);

    /**
     * Get all timetable entries with optional filtering, relations, and pagination.
     *
     * @param string|array|null $relations
     * @param int|null $limit
     * @param int|null $offset
     * @param bool|null $noPagination
     * @param int|null $pagPerPage
     * @param array|null $conds Additional search conditions (classroom_id, section_id, course_id, day_of_week, teacher_id, status)
     * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);

    /**
     * Create a new timetable entry.
     *
     * @param array $data
     * @return \Modules\TimeTable\app\Models\TimeTable
     */
    public function create($data);

    /**
     * Update an existing timetable entry.
     *
     * @param int $id
     * @param array $data
     * @return \Modules\TimeTable\app\Models\TimeTable
     */
    public function update($id, $data);

    /**
     * Delete a timetable entry.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id);

    /**
     * Get timetable by classroom and day.
     *
     * @param int $classroomId
     * @param string $dayOfWeek
     * @param string|array|null $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByClassroomAndDay($classroomId, $dayOfWeek, $relations = null);

    /**
     * Get timetable by teacher.
     *
     * @param int $teacherId
     * @param string|array|null $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByTeacher($teacherId, $relations = null);
}
