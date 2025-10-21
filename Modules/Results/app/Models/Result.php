<?php

namespace Modules\Results\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Modules\Courses\Models\Course;
use Modules\Exams\Models\Exam;
use Modules\Users\Models\User;

class Result extends Model
{
    protected $table = 'results';

    protected $fillable = [
        'student_id',
        'exam_id',
        'course_id',
        'marks',
        'grade',
        'remarks',
        'grading_scale_id'
    ];

    protected $casts = [
        'marks' => 'float'
    ];

    protected $appends = ['status', 'percentage'];

    protected static function boot()
    {
        parent::boot();

        // Always load these relationships
        static::with(['student', 'exam', 'course']);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id')
            ->withDefault(['name' => 'Deleted Student']);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class)
            ->withDefault(['title' => 'Deleted Exam']);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class)
            ->withDefault(['name' => 'Deleted Course']);
    }

    // Scopes for common queries
    public function scopePassing(Builder $query): Builder
    {
        return $query->whereColumn('marks', '>=', 'exam.passing_marks');
    }

    public function scopeFailing(Builder $query): Builder
    {
        return $query->whereColumn('marks', '<', 'exam.passing_marks');
    }

    // Helper Methods
    public function getStatusAttribute(): string
    {
        if (!$this->exam) return 'N/A';
        return $this->marks >= $this->exam->passing_marks ? 'Pass' : 'Fail';
    }

    public function getPercentageAttribute(): float
    {
        if (!$this->exam || !$this->exam->total_marks) return 0;
        return ($this->marks / $this->exam->total_marks) * 100;
    }

    public function getLetterGradeAttribute(): string
    {
        $percentage = $this->getPercentageAttribute();

        return match(true) {
            $percentage >= 90 => 'A+',
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B',
            $percentage >= 60 => 'C',
            $percentage >= 50 => 'D',
            default => 'F'
        };
    }

    public function isHighestInClass(): bool
    {
        return $this->exam->results()
            ->where('marks', '>', $this->marks)
            ->doesntExist();
    }

    public function getRank(): int
    {
        return $this->exam->results()
            ->where('marks', '>', $this->marks)
            ->count() + 1;
    }

    public function getPerformanceStatus(): array
    {
        $examAverage = $this->exam->results()->avg('marks');

        return [
            'score' => $this->marks,
            'max_score' => $this->exam->total_marks,
            'class_average' => round($examAverage, 2),
            'difference_from_average' => round($this->marks - $examAverage, 2),
            'percentile' => $this->calculatePercentile(),
            'rank' => $this->getRank()
        ];
    }

    protected function calculatePercentile(): float
    {
        $totalStudents = $this->exam->results()->count();
        $studentsBelow = $this->exam->results()
            ->where('marks', '<', $this->marks)
            ->count();

        return round(($studentsBelow / $totalStudents) * 100, 2);
    }
}
