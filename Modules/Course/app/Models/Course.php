<?php
// Modules/Course/app/Models/Course.php

namespace Modules\Course\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Subject\App\Models\Subject;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_name',
        'description',
        'category'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

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
     * Relationship with enrollments
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
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
}
