<?php

namespace Modules\Users\Teachers\Services;

interface TeacherApiServiceInterface
{
    /**
     * Get all teachers with pagination
     */
    public function index(int $perPage = 10);

    /**
     * Get a specific teacher by UUID
     */
    public function show(string $uuid);

    /**
     * Create a new teacher
     */
    public function store(array $data);

    /**
     * Update a teacher
     */
    public function update(string $uuid, array $data);

    /**
     * Delete a teacher
     */
    public function destroy(string $uuid);

    /**
     * Get teachers by department
     */
    public function getByDepartment(int $departmentId);

    /**
     * Get teachers teaching a specific subject
     */
    public function getTeachersForSubject(int $subjectId);

    /**
     * Get teacher's assigned courses
     */
    public function getTeacherCourses(string $teacherUuid);

    /**
     * Get teacher's assigned subjects
     */
    public function getTeacherSubjects(string $teacherUuid);

    /**
     * Assign subject to teacher
     */
    public function assignSubject(string $teacherUuid, int $subjectId);

    /**
     * Remove subject from teacher
     */
    public function removeSubject(string $teacherUuid, int $subjectId);

    /**
     * Assign course to teacher
     */
    public function assignCourse(string $teacherUuid, int $courseId);

    /**
     * Remove course from teacher
     */
    public function removeCourse(string $teacherUuid, int $courseId);
}
