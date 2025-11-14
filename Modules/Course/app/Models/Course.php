<?php
// Modules/Course/app/Models/Course.php

namespace Modules\Course\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Subject\app\Models\Subject;
use Modules\Users\User\App\Models\User;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'course_name',
        'description',
        'category'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Boot function to auto-generate UUID on create
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationship with subjects with pivot data
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'course_subject')
                    ->withTimestamps()
                    ->withPivot('created_at', 'updated_at');
    }

    /**
     * Relationship with student enrollments
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Relationship with enrolled students
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'user_id')
                    ->withTimestamps()
                    ->withPivot(['enrollment_date', 'status']);
    }

    /**
     * Relationship with teachers
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'course_teacher', 'course_id', 'user_id')
                    ->withTimestamps()
                    ->withPivot(['assigned_date', 'status']);
    }

    /**
     * Scope for active courses
     */
    public function scopeActive($query)
    {
        return $query->whereHas('enrollments', function ($q) {
            $q->where('status', 'active');
        });
    }

    /**
     * Get subjects count for a course
     */
    public function getSubjectsCountAttribute(): int
    {
        return $this->subjects()->count();
    }

    /**
     * Get active students count
     */
    public function getActiveStudentsCountAttribute(): int
    {
        return $this->students()->wherePivot('status', 'active')->count();
    }

    /**
     * Get all students count
     */
    public function getStudentsCountAttribute(): int
    {
        return $this->students()->count();
    }

    /**
     * Check if course has a specific subject
     */
    public function hasSubject(int $subjectId): bool
    {
        return $this->subjects()->where('subject_id', $subjectId)->exists();
    }

    /**
     * Add subject to course
     */
    public function addSubject(int $subjectId): void
    {
        if (!$this->hasSubject($subjectId)) {
            $this->subjects()->attach($subjectId);
        }
    }

    /**
     * Remove subject from course
     */
    public function removeSubject(int $subjectId): void
    {
        $this->subjects()->detach($subjectId);
    }

    /**
     * Sync subjects for course (replace all existing subjects)
     */
    public function syncSubjects(array $subjectIds): void
    {
        $this->subjects()->sync($subjectIds);
    }

    /**
     * Enroll a student in the course
     */
    public function enrollStudent(int $studentId, string $status = 'active'): void
    {
        if (!$this->students()->where('user_id', $studentId)->exists()) {
            $this->students()->attach($studentId, [
                'enrollment_date' => now(),
                'status' => $status
            ]);
        }
    }

    /**
     * Remove student from course
     */
    public function removeStudent(int $studentId): void
    {
        $this->students()->detach($studentId);
    }

    /**
     * Update student enrollment status
     */
    public function updateStudentStatus(int $studentId, string $status): void
    {
        $this->students()->updateExistingPivot($studentId, ['status' => $status]);
    }

    /**
     * Check if student is enrolled in course
     */
    public function hasStudent(int $studentId): bool
    {
        return $this->students()->where('user_id', $studentId)->exists();
    }
}
