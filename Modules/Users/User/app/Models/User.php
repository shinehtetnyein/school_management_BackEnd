<?php

namespace Modules\Users\User\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Modules\Course\App\Models\Course;
use Modules\Results\Models\Result;
use Modules\Subject\App\Models\Subject;
use Spatie\Permission\Traits\HasRoles;
use App\Console\Enums\Role;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable, HasRoles;

    protected $guard_name = 'sanctum';

    protected $appends = ['role'];

    // Constants matching actual database columns
    const id = 'id';
    const email = 'email';
    const name = 'name';
    const password = 'password';
    const phone_no = 'phone_no';
    const address = 'address';
    const date_of_birth = 'date_of_birth';
    const gender = 'gender';
    const profile_photo = 'profile_photo';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_no',
        'address',
        'date_of_birth',
        'gender',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'datetime',
        'password' => 'hashed'
    ];

    // Relationships
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_student', 'user_id', 'course_id')
            ->withTimestamps()
            ->withPivot(['enrollment_date', 'status']);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'user_id');
    }

    public function teachingSubjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher', 'user_id', 'subject_id')
            ->withTimestamps()
            ->withPivot(['assigned_date', 'status']);
    }

    public function teachingCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_teacher', 'user_id', 'course_id')
            ->withTimestamps()
            ->withPivot(['assigned_date', 'status']);
    }

    // Role-based Helper Methods using the Enum
    public function isStudent(): bool
    {
        return $this->hasRole(Role::STUDENT->value);
    }

    public function isTeacher(): bool
    {
        return $this->hasRole(Role::TEACHER->value);
    }

    public function isStaff(): bool
    {
        return in_array($this->getPrimaryRole(), [
            Role::ADMIN->value,
            Role::LIBRARIAN->value,
            Role::ACCOUNTANT->value,
        ]);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Role::ADMIN->value);
    }

    public function isRootAdmin(): bool
    {
        return $this->hasRole(Role::ROOT_ADMIN->value);
    }

    public function isParent(): bool
    {
        return $this->hasRole(Role::PARENT->value);
    }

    public function isLibrarian(): bool
    {
        return $this->hasRole(Role::LIBRARIAN->value);
    }

    public function isAccountant(): bool
    {
        return $this->hasRole(Role::ACCOUNTANT->value);
    }

    public function isGuest(): bool
    {
        return $this->hasRole(Role::GUEST->value);
    }

    /**
     * Get the primary role (first role assigned to user)
     */
    public function getPrimaryRole(): ?string
    {
        return $this->getRoleNames()->first();
    }

    /**
     * Get the Role enum instance for the user's primary role
     */
    public function getRoleEnum(): ?Role
    {
        $roleName = $this->getPrimaryRole();
        return $roleName ? Role::tryFrom($roleName) : null;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        $roleValues = array_map(fn($role) => $role instanceof Role ? $role->value : $role, $roles);
        return $this->hasAnyRole($roleValues);
    }

    /**
     * Assign a role using Role enum
     */
    public function assignRoleEnum(Role $role): self
    {
        return $this->assignRole($role->value);
    }

    /**
     * Sync roles using Role enums
     */
    public function syncRoleEnums(array $roles): self
    {
        $roleValues = array_map(fn($role) => $role instanceof Role ? $role->value : $role, $roles);
        return $this->syncRoles($roleValues);
    }

    // Additional methods
    public function getFullName(): string
    {
        return $this->name;
    }

    public function getIdentificationNumber(): string
    {
        return $this->email;
    }

    public function getRoleAttribute()
    {
        $roleEnum = $this->getRoleEnum();
        return $roleEnum ? $roleEnum->value : null;
    }

    public function getRoleLabelAttribute(): ?string
    {
        $roleEnum = $this->getRoleEnum();
        return $roleEnum ? $roleEnum->label() : null;
    }

    // Academic Performance Methods (for students)
    public function getCurrentCourses()
    {
        if (!$this->isStudent()) return null;

        return $this->enrolledCourses()
            ->wherePivot('status', true)
            ->get();
    }

    public function getAcademicProgress(): array
    {
        if (!$this->isStudent()) return [];

        $results = $this->results()
            ->with('exam', 'course')
            ->get();

        $totalExams = $results->count();
        if (!$totalExams) return [
            'average_score' => 0,
            'pass_rate' => 0,
            'total_exams' => 0,
            'courses_completed' => 0
        ];

        $passedExams = $results->filter(function($result) {
            return $result->status === 'Pass';
        })->count();

        $coursesCompleted = $this->enrolledCourses()
            ->wherePivot('status', 'completed')
            ->count();

        return [
            'average_score' => $results->avg('marks'),
            'pass_rate' => ($passedExams / $totalExams) * 100,
            'total_exams' => $totalExams,
            'courses_completed' => $coursesCompleted
        ];
    }

    // Teaching Methods (for teachers)
    public function getCurrentTeachingLoad()
    {
        if (!$this->isTeacher()) return null;

        return $this->teachingCourses()
            ->wherePivot('status', true)
            ->with(['subject', 'students'])
            ->get()
            ->map(function($course) {
                return [
                    'course_name' => $course->name,
                    'subject' => $course->subject->name ?? 'N/A',
                    'students_count' => $course->students->count(),
                    'schedule' => $course->schedule ?? []
                ];
            });
    }

    public function getTeachingStatistics(): array
    {
        if (!$this->isTeacher()) return [];

        $courses = $this->teachingCourses()->wherePivot('status', true)->get();
        $totalStudents = 0;
        $totalPassed = 0;
        $totalExams = 0;

        foreach ($courses as $course) {
            $totalStudents += $course->students()->count();
            $exams = $course->exams;
            $totalExams += $exams->count();

            foreach ($exams as $exam) {
                $totalPassed += $exam->results()
                    ->where('marks', '>=', $exam->passing_marks)
                    ->count();
            }
        }

        return [
            'total_courses' => $courses->count(),
            'total_students' => $totalStudents,
            'total_exams' => $totalExams,
            'average_pass_rate' => $totalExams ? ($totalPassed / $totalExams) * 100 : 0
        ];
    }

    // Utility methods
    public function getFormattedPhone(): ?string
    {
        return $this->phone_no ?: null;
    }

    public function getAge(): ?int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    /**
     * Scope for students
     */
    public function scopeStudents($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->where('name', Role::STUDENT->value);
        });
    }

    /**
     * Scope for teachers
     */
    public function scopeTeachers($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->where('name', Role::TEACHER->value);
        });
    }

    /**
     * Scope for admins
     */
    public function scopeAdmins($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->whereIn('name', [Role::ADMIN->value, Role::ROOT_ADMIN->value]);
        });
    }
}