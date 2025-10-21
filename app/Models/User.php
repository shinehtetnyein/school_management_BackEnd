<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'phone_no',
        'address',
        'image_url',
        'academic_year_id', // Added for AcademicYear relationship
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function enrollments(): HasMany
    // {
    //     return $this->hasMany(Enrollment::class, 'user_id');
    // }

    // public function attendances(): HasMany
    // {
    //     return $this->hasMany(Attendance::class, 'user_id');
    // }

    // public function payments(): HasMany
    // {
    //     return $this->hasManyThrough(Payment::class, Account::class, 'user_id', 'account_id');
    // }

    // public function events(): HasMany
    // {
    //     return $this->hasMany(Event::class, 'user_id');
    // }

    // public function borrowedBooks(): HasManyThrough
    // {
    //     return $this->hasManyThrough(Book::class, BorrowRecord::class, 'user_id', 'id', 'id', 'book_id');
    // }

    // public function borrowRecords(): HasMany
    // {
    //     return $this->hasMany(BorrowRecord::class, 'user_id');
    // }

    // public function exams(): HasMany
    // {
    //     return $this->hasMany(Exam::class, 'user_id');
    // }

    // public function examResults(): HasMany
    // {
    //     return $this->hasMany(ExamResult::class, 'user_id');
    // }

    // public function homework(): HasMany
    // {
    //     return $this->hasMany(Homework::class, 'user_id');
    // }

    // public function communications(): BelongsToMany
    // {
    //     return $this->belongsToMany(Communication::class, 'communication_users', 'user_id', 'communication_id');
    // }

    // public function courses(): BelongsToMany
    // {
    //     return $this->belongsToMany(Course::class, 'enrollments', 'user_id', 'course_id');
    // }

    // public function account(): HasOne
    // {
    //     return $this->hasOne(Account::class, 'user_id');
    // }

    // public function roles(): BelongsToMany
    // {
    //     return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    // }

    // public function permissions(): BelongsToMany
    // {
    //     return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    // }

    // public function classrooms(): BelongsToMany
    // {
    //     return $this->belongsToMany(Classroom::class, 'attendance_classroom', 'user_id', 'class_room_id')
    //         ->using(AttendanceClassroom::class);
    // }

    // public function resources(): HasMany
    // {
    //     return $this->hasMany(Resource::class, 'user_id');
    // }

    // public function subjects(): BelongsToMany
    // {
    //     return $this->belongsToMany(Subject::class, 'course_subjects', 'course_id', 'subject_id')
    //         ->using(CourseSubject::class);
    // }

    // public function timetables(): BelongsToMany
    // {
    //     return $this->belongsToMany(Timetable::class, 'classroom_timetable', 'classroom_id', 'timetable_id')
    //         ->using(ClassroomTimetable::class);
    // }

    // public function academicYear(): BelongsTo
    // {
    //     return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    // }
}
