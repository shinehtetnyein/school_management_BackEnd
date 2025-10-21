<?php

namespace Modules\AcademicYears\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Courses\Models\Course;

class AcademicYear extends Model
{
    protected $table = 'academic_years';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_current',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_current' => 'boolean',
        'status' => 'boolean'
    ];

    protected $appends = ['is_active', 'progress_percentage'];

    protected static function boot()
    {
        parent::boot();

        // When setting a new current year, unset others
        static::saving(function ($academicYear) {
            if ($academicYear->is_current) {
                static::where('id', '!=', $academicYear->id)
                    ->update(['is_current' => false]);
            }
        });
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    // Helper Methods
    public function getIsActiveAttribute(): bool
    {
        $now = now();
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    public function getProgressPercentageAttribute(): float
    {
        $now = now();
        if ($now < $this->start_date) return 0;
        if ($now > $this->end_date) return 100;

        $totalDays = $this->start_date->diffInDays($this->end_date);
        $daysPassed = $this->start_date->diffInDays($now);

        return min(100, round(($daysPassed / $totalDays) * 100, 2));
    }

    public function getStatistics(): array
    {
        return [
            'year_info' => [
                'name' => $this->name,
                'duration' => $this->start_date->format('M Y') . ' - ' . $this->end_date->format('M Y'),
                'is_current' => $this->is_current,
                'is_active' => $this->is_active,
                'progress' => $this->progress_percentage . '%'
            ],
            'courses' => [
                'total' => $this->courses()->count(),
                'active' => $this->courses()->active()->count()
            ],
            'students' => $this->getStudentStats(),
            'performance' => $this->getPerformanceStats()
        ];
    }

    protected function getStudentStats(): array
    {
        $totalEnrollments = 0;
        $activeEnrollments = 0;

        foreach ($this->courses as $course) {
            $totalEnrollments += $course->students()->count();
            $activeEnrollments += $course->students()->where('status', true)->count();
        }

        return [
            'total_enrollments' => $totalEnrollments,
            'active_enrollments' => $activeEnrollments
        ];
    }

    protected function getPerformanceStats(): array
    {
        $courses = $this->courses()->with('results')->get();
        $totalResults = 0;
        $totalMarks = 0;
        $passedCount = 0;

        foreach ($courses as $course) {
            foreach ($course->results as $result) {
                $totalResults++;
                $totalMarks += $result->marks;
                if ($result->status === 'Pass') {
                    $passedCount++;
                }
            }
        }

        return [
            'average_score' => $totalResults ? round($totalMarks / $totalResults, 2) : 0,
            'pass_rate' => $totalResults ? round(($passedCount / $totalResults) * 100, 2) : 0
        ];
    }
}
