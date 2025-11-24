<?php

namespace Modules\Users\Students\Services\Implementations;
use Modules\Users\Students\Services\StudentApiServiceInterface;
use Modules\Users\User\app\Models\User;
use App\Console\Enums\Role;

class StudentApiService implements StudentApiServiceInterface
{
    public function getAllStudents()
    {
        // Use scopeStudents and eager load related academic data for frontend
        return User::students()
            ->with([
                'enrolledCourses',
                'subjects',
                'classroom',
                'section',
                'results',
            ])
            ->orderBy('id', 'asc')
            ->get();
    }

public function createStudent(array $data)
{
        // Expect frontend to provide `roll_no` directly
        $data['role'] = Role::STUDENT->value;

    // Create the student user
    $user = User::create($data);
    $user->assignRoleEnum(Role::STUDENT);

    // Attach relationships (1 course, 1 section, 1 subject)
    if (isset($data['course_id'])) {
        $courseId = $data['course_id'];
        $pivot = [];
        if (!empty($data['enrollment_date'])) {
            $pivot['enrollment_date'] = $data['enrollment_date'];
        }
        // sync with pivot data if provided
        if (!empty($pivot)) {
            $user->courses()->sync([$courseId => $pivot]);
        } else {
            $user->courses()->sync([$courseId]);
        }
    }

    if (isset($data['section_id'])) {
        $user->section()->sync([$data['section_id']]);
    }

    if (isset($data['subject_id'])) {
        $user->subjects()->sync([$data['subject_id']]);
    }

     if (!empty($data['classroom_id'])) {
        $user->classroom()->sync([$data['classroom_id']]);
    }


    return $user;
}



    public function getStudentById($id)
    {
        // eager load relations so single request returns related academic data
        $user = User::with([
            'enrolledCourses',
            'subjects',
            'classroom',
            'section',
            'results',
        ])->findOrFail($id);
        if ($user->hasRole(Role::STUDENT->value)) {
            return $user;
        }
        abort(404, 'Student not found');
    }

    public function updateStudent($id, array $data)
    {
        $user = User::findOrFail($id);
        if ($user->hasRole(Role::STUDENT->value)) {
            // Update main user fields first (frontend should provide `roll_no`)
            $user->update($data);

            // If enrollment_date provided together with course_id, update pivot
            if (!empty($data['course_id']) && array_key_exists('enrollment_date', $data)) {
                $courseId = $data['course_id'];
                $enrollment = $data['enrollment_date'];
                // ensure pivot exists; use sync without detaching other courses
                $existing = $user->courses()->where('course_id', $courseId)->exists();
                if ($existing) {
                    $user->courses()->updateExistingPivot($courseId, ['enrollment_date' => $enrollment]);
                } else {
                    $user->courses()->attach($courseId, ['enrollment_date' => $enrollment]);
                }
            }
            return $user;
        }
        abort(404, 'Student not found');
    }

    public function deleteStudent($id)
    {
        $user = User::findOrFail($id);
        if ($user->hasRole(Role::STUDENT->value)) {
            $user->delete();
            return;
        }
        abort(404, 'Student not found');
    }
}
