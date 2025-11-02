<?php

namespace Modules\Users\User\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Modules\Courses\Models\Course;
use Modules\Results\Models\Result;
use Modules\Subjects\Models\Subject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable, HasRoles;

    protected $guard_name = 'sanctum'; // match your roles

    protected $appends = ['role'];

    const id = 'id';
    const academic_year_id = 'academic_year_id';
    const email = 'email';
    const type = 'type';
    const student_id = 'student_id';
    const staff_id = 'staff_id';
    const name = 'name';
    const password = 'password';
    const phone = 'phone';
    const address = 'address';
    const date_of_birth = 'date_of_birth';
    const gender = 'gender';
    const profile_photo = 'profile_photo';
    const status = 'status';
    const joined_date = 'joined_date';

    protected $fillable = [
        'name',
        'academic_year_id',
        'email',
        'password',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'profile_photo',
        'type', // student, teacher, staff
        'student_id', // for students
        'staff_id',   // for teachers and staff
        'status',
        'joined_date'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'joined_date' => 'date',
        'status' => 'boolean',
        'password' => 'hashed'
    ];

    // Relationships for Students
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_student', 'student_id', 'course_id')
            ->withTimestamps()
            ->withPivot(['enrollment_date', 'status']);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'student_id');
    }

    // Relationships for Teachers
    public function teachingSubjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher', 'teacher_id', 'subject_id')
            ->withTimestamps()
            ->withPivot(['assigned_date', 'status']);
    }

    public function teachingCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_teacher', 'teacher_id', 'course_id')
            ->withTimestamps()
            ->withPivot(['assigned_date', 'status']);
    }

    // Helper Methods
    public function isStudent(): bool
    {
        return $this->type === 'student';
    }

    public function isTeacher(): bool
    {
        return $this->type === 'teacher';
    }

    public function isStaff(): bool
    {
        return $this->type === 'staff';
    }

    public function getFullName(): string
    {
        return $this->name;
    }

    public function getIdentificationNumber(): string
    {
        return $this->isStudent() ? $this->student_id : $this->staff_id;
    }

    // Academic Performance Methods (for students)
    public function getCurrentCourses()
    {
        if (!$this->isStudent()) return null;

        return $this->enrolledCourses()
            ->whereHas('academicYear', function($query) {
                $query->where('is_current', true);
            })
            ->where('status', true)
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

        return [
            'average_score' => $results->avg('marks'),
            'pass_rate' => ($passedExams / $totalExams) * 100,
            'total_exams' => $totalExams,
            'courses_completed' => $this->enrolledCourses()
                ->whereHas('academicYear', function($query) {
                    $query->where('end_date', '<', now());
                })
                ->count()
        ];
    }

    // Teaching Methods (for teachers)
    public function getCurrentTeachingLoad()
    {
        if (!$this->isTeacher()) return null;

        return $this->teachingCourses()
            ->whereHas('academicYear', function($query) {
                $query->where('is_current', true);
            })
            ->where('status', true)
            ->with(['subject', 'students'])
            ->get()
            ->map(function($course) {
                return [
                    'course_name' => $course->name,
                    'subject' => $course->subject->name,
                    'students_count' => $course->students->count(),
                    'schedule' => $course->schedule ?? []
                ];
            });
    }

    public function getTeachingStatistics(): array
    {
        if (!$this->isTeacher()) return [];

        $courses = $this->teachingCourses;
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
}
