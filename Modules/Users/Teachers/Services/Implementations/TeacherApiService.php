<?php

namespace Modules\Users\Teachers\Services\Implementations;

use Modules\Users\User\App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Console\Enums\Role;
use Modules\Users\Teachers\Services\TeacherApiServiceInterface;

class TeacherApiService implements TeacherApiServiceInterface
{
    /**
     * Get all teachers with pagination
     */
    public function index(int $perPage = 10)
    {
        // Return Eloquent collection of User models so resources can access model properties
        $teachers = User::whereHas('roles', function($q) {
            $q->where('name', Role::TEACHER->label());
        })
        ->with(['teachingSubjects', 'teachingCourses'])
        ->orderBy('id', 'asc')
        ->get();

        return [
            'total_count' => $teachers->count(),
            'teachers' => $teachers,
        ];
    }

    /**
     * Get a specific teacher by UUID
     */
    public function show(string $uuid)
    {
        return User::whereHas('roles', function($q) {
            $q->where('name', Role::TEACHER->label());
        })
        ->with(['teachingSubjects', 'teachingCourses', 'results'])
        ->where('uuid', $uuid)
        ->firstOrFail();
    }

    /**
     * Create a new teacher
     */
    public function store(array $data)
    {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Generate UUID if not provided
        if (!isset($data['uuid'])) {
            $data['uuid'] = \Illuminate\Support\Str::uuid();
        }

        // Create the user
        $teacher = User::create($data);

        // Assign teacher role
        $teacher->assignRole(Role::TEACHER->label());

        return $teacher->load(['teachingSubjects', 'teachingCourses']);
    }

    /**
     * Update a teacher
     */
    public function update(string $uuid, array $data)
    {
        $teacher = User::whereHas('roles', function($q) {
            $q->where('name', Role::TEACHER->label());
        })->where('uuid', $uuid)->firstOrFail();

        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Remove password from update if not provided
            unset($data['password']);
        }

        $teacher->update($data);

        return $teacher->load(['teachingSubjects', 'teachingCourses']);
    }

    /**
     * Delete a teacher
     */
    public function destroy(string $uuid)
    {
        $teacher = User::whereHas('roles', function($q) {
            $q->where('name', Role::TEACHER->label());
        })->where('uuid', $uuid)->firstOrFail();

        // Detach all subjects and courses
        $teacher->teachingSubjects()->detach();
        $teacher->teachingCourses()->detach();

        // Remove role
        $teacher->removeRole(Role::TEACHER->label());

        // Delete the user
        $teacher->delete();

        return true;
    }

    /**
     * Get teachers by department
     */
    public function getByDepartment(int $departmentId)
    {
        return User::whereHas('roles', function($q) {
            $q->where('name', Role::TEACHER->label());
        })
        ->whereHas('teachingSubjects', function($q) use ($departmentId) {
            $q->where('department_id', $departmentId);
        })
        ->with(['teachingSubjects', 'teachingCourses'])
        ->orderBy('id', 'asc')
        ->get();
    }

    /**
     * Get teachers teaching a specific subject
     */
    public function getTeachersForSubject(int $subjectId)
    {
        return User::whereHas('roles', function($q) {
            $q->where('name', Role::TEACHER->label());
        })
        ->whereHas('teachingSubjects', function($q) use ($subjectId) {
            $q->where('subject_id', $subjectId);
        })
        ->with(['teachingSubjects', 'teachingCourses'])
        ->orderBy('id', 'asc')
        ->get();
    }

    /**
     * Get teacher's assigned courses
     */
    public function getTeacherCourses(string $teacherUuid)
    {
        $teacher = User::whereHas('roles', function($q) {
            $q->where('name', Role::TEACHER->label());
        })->where('uuid', $teacherUuid)->firstOrFail();

        return $teacher->teachingCourses()
            ->wherePivot('status', 1)
            ->with('subjects')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Get teacher's assigned subjects
     */
    public function getTeacherSubjects(string $teacherUuid)
    {
        $teacher = User::whereHas('roles', function($q) {
            $q->where('name', Role::TEACHER->label());
        })->where('uuid', $teacherUuid)->firstOrFail();

        return $teacher->teachingSubjects()
            ->wherePivot('status', 1)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Assign subject to teacher
     */
    public function assignSubject(string $teacherUuid, int $subjectId)
    {
        $teacher = User::whereHas('roles', function($q) {
            $q->where('name', Role::TEACHER->label());
        })->where('uuid', $teacherUuid)->firstOrFail();

        // Check if already assigned
        if (!$teacher->teachingSubjects()->where('subject_id', $subjectId)->exists()) {
            $teacher->teachingSubjects()->attach($subjectId, [
                'assigned_date' => now(),
                'status' => 1
            ]);
        }

        return $teacher->teachingSubjects;
    }

    /**
     * Remove subject from teacher
     */
    public function removeSubject(string $teacherUuid, int $subjectId)
    {
        $teacher = User::whereHas('roles', function($q) {
            $q->where('name', Role::TEACHER->label());
        })->where('uuid', $teacherUuid)->firstOrFail();

        $teacher->teachingSubjects()->detach($subjectId);

        return true;
    }

    /**
     * Assign course to teacher
     */
    public function assignCourse(string $teacherUuid, int $courseId)
    {
        $teacher = User::whereHas('roles', function($q) {
            $q->where('name', Role::TEACHER->label());
        })->where('uuid', $teacherUuid)->firstOrFail();

        // Check if already assigned
        if (!$teacher->teachingCourses()->where('course_id', $courseId)->exists()) {
            $teacher->teachingCourses()->attach($courseId, [
                'assigned_date' => now(),
                'status' => 1
            ]);
        }

        return $teacher->teachingCourses;
    }

    /**
     * Remove course from teacher
     */
    public function removeCourse(string $teacherUuid, int $courseId)
    {
        $teacher = User::whereHas('roles', function($q) {
            $q->where('name', Role::TEACHER->label());
        })->where('uuid', $teacherUuid)->firstOrFail();

        $teacher->teachingCourses()->detach($courseId);

        return true;
    }
}
