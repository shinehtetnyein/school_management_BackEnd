<?php
// Modules/Subject/app/Models/Subject.php

namespace Modules\Subject\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Course\App\Models\Course;
use Modules\AcademicYears\App\Models\AcademicYear;
use Modules\Exams\App\Models\Exam;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_code',
        'subject_name',
        'subject_desc',
        'class_level',
        'status',
        // 'exam_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the status enum values
     */
    public static function getStatusOptions(): array
    {
        return ['active', 'inactive'];
    }

    /**
     * Get the class level enum values
     */
    public static function getClassLevelOptions(): array
    {
        return ['beginner', 'intermediate', 'advanced'];
    }

    /**
     * Relationship with courses with pivot data
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_subject')
                    ->withTimestamps()
                    ->withPivot('created_at', 'updated_at');
    }

    /**
     * Relationship with academic years through courses
     */
    public function academicYears()
    {
        return $this->hasManyThrough(
            AcademicYear::class,
            Course::class,
            'id', // Foreign key on courses table...
            'id', // Foreign key on academic_years table...
            'id', // Local key on subjects table...
            'academic_year_id' // Local key on courses table...
        );
    }

    /**
     * Relationship with exam
     */
    // public function exam()
    // {
    //     return $this->belongsTo(Exam::class, 'exam_id');
    // }

    /**
     * Scope for active subjects
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for subjects by class level
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('class_level', $level);
    }

    /**
     * Scope for searching subjects
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('subject_name', 'like', "%{$searchTerm}%")
              ->orWhere('subject_code', 'like', "%{$searchTerm}%")
              ->orWhere('subject_desc', 'like', "%{$searchTerm}%");
        });
    }

    /**
     * Get courses count for a subject
     */
    public function getCoursesCountAttribute(): int
    {
        return $this->courses()->count();
    }

    /**
     * Check if subject is assigned to a specific course
     */
    public function isAssignedToCourse(int $courseId): bool
    {
        return $this->courses()->where('course_id', $courseId)->exists();
    }
}
