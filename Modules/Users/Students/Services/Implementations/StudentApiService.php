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
        // Ensure required fields for new schema
        $data['role'] = Role::STUDENT->value;
        $user = User::create($data);
        $user->assignRoleEnum(Role::STUDENT);
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
            $user->update($data);
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
