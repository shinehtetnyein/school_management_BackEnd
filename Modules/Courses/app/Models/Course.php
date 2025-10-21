<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\AcademicYears\Models\AcademicYear;
use Modules\Homework\Models\Homework;
use Modules\Exams\Models\Exam;
use Modules\Results\Models\Result;
use Modules\Subjects\Models\Subject;
use Modules\Users\Models\User;

class Course extends Model
{
    protected $table = 'courses';

    protected $fillable = [
        'name',
        'code',
        'description',
        'academic_year_id',
        'subject_id',
        'status',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'status' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    protected $appends = ['completion_percentage', 'student_count', 'average_grade'];

    protected static function boot()
    {
        parent::boot();

        // When a course is deleted, cascade to related models
        static::deleting(function ($course) {
            $course->homeworks()->delete();
            $course->exams()->delete();
            $course->results()->delete();
        });
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class)
            ->withDefault(['name' => 'Deleted Subject']);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class)
            ->withDefault(['name' => 'Deleted Academic Year']);
    }

    public function homeworks(): HasMany
    {
        return $this->hasMany(Homework::class)
            ->latest();
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class)
            ->orderBy('exam_date');
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'student_id')
            ->withTimestamps()
            ->withPivot(['enrollment_date', 'status']);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeCurrentYear($query)
    {
        return $query->whereHas('academicYear', function ($q) {
            $q->where('is_current', true);
        });
    }

    // Helper Methods
    public function getCompletionPercentageAttribute(): float
    {
        $now = now();
        if (!$this->start_date || !$this->end_date) return 0;

        $totalDays = $this->start_date->diffInDays($this->end_date);
        $daysPassed = $this->start_date->diffInDays($now);

        return min(100, round(($daysPassed / $totalDays) * 100, 2));
    }

    public function getStudentCountAttribute(): int
    {
        return $this->students()->where('status', true)->count();
    }

    public function getAverageGradeAttribute(): float
    {
        return $this->results()
            ->whereHas('exam', function($query) {
                $query->where('status', true);
            })
            ->avg('marks') ?? 0;
    }

    public function getTopPerformers(int $limit = 5): array
    {
        return $this->students()
            ->withAvg('results', 'marks')
            ->orderByDesc('results_avg_marks')
            ->take($limit)
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'average_marks' => round($student->results_avg_marks, 2)
                ];
            })
            ->toArray();
    }

    public function getAttendanceStats(): array
    {
        $totalClasses = $this->attendance()->count();
        $stats = [];

        foreach ($this->students as $student) {
            $present = $student->attendance()
                ->where('course_id', $this->id)
                ->where('status', 'present')
                ->count();

            $stats[$student->id] = [
                'student_name' => $student->name,
                'attendance_percentage' => $totalClasses ? round(($present / $totalClasses) * 100, 2) : 0
            ];
        }

        return $stats;
    }

    public function getProgressReport(): array
    {
        return [
            'course_info' => [
                'name' => $this->name,
                'code' => $this->code,
                'completion' => $this->completion_percentage
            ],
            'stats' => [
                'total_students' => $this->student_count,
                'average_grade' => $this->average_grade,
                'pass_rate' => $this->getPassRate(),
                'upcoming_exams' => $this->exams()->upcoming()->count()
            ],
            'top_performers' => $this->getTopPerformers(),
            'attendance' => $this->getAttendanceStats()
        ];
    }

    protected function getPassRate(): float
    {
        $totalResults = $this->results()->count();
        if (!$totalResults) return 0;

        $passedResults = $this->results()
            ->whereHas('exam', function($query) {
                $query->whereColumn('marks', '>=', 'passing_marks');
            })
            ->count();

        return round(($passedResults / $totalResults) * 100, 2);
    }
}
