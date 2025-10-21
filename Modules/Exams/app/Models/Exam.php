<?php

namespace Modules\Exams\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Modules\Courses\Models\Course;
use Modules\Results\Models\Result;
use Carbon\Carbon;

class Exam extends Model
{
    protected $table = 'exams';

    protected $fillable = [
        'title',
        'course_id',
        'exam_date',
        'duration',
        'total_marks',
        'passing_marks',
        'description',
        'status',
        'type'
    ];

    protected $casts = [
        'exam_date' => 'datetime',
        'duration' => 'integer',
        'total_marks' => 'float',
        'passing_marks' => 'float',
        'status' => 'boolean'
    ];

    protected $appends = [
        'passing_percentage',
        'average_score',
        'highest_score',
        'is_completed'
    ];

    protected static function boot()
    {
        parent::boot();

        // When an exam is deleted, cascade to results
        static::deleting(function ($exam) {
            $exam->results()->delete();
        });
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class)
            ->withDefault(['name' => 'Deleted Course']);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class)
            ->orderBy('marks', 'desc');
    }

    // Scopes
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('exam_date', '>', now())
                    ->where('status', true)
                    ->orderBy('exam_date');
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->where('exam_date', '<', now())
                    ->orderBy('exam_date', 'desc');
    }

    // Helper methods
    public function getPassingPercentageAttribute(): float
    {
        if (!$this->results()->exists()) {
            return 0;
        }

        $totalStudents = $this->results()->count();
        $passedStudents = $this->results()
            ->where('marks', '>=', $this->passing_marks)
            ->count();

        return round(($passedStudents / $totalStudents) * 100, 2);
    }

    public function getAverageScoreAttribute(): float
    {
        return round($this->results()->avg('marks') ?? 0, 2);
    }

    public function getHighestScoreAttribute(): float
    {
        return round($this->results()->max('marks') ?? 0, 2);
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->exam_date && $this->exam_date->isPast();
    }

    public function getDurationForHumans(): string
    {
        return Carbon::createFromTimestamp($this->duration * 60)->diffForHumans(null, true);
    }

    public function getTimeRemaining(): ?string
    {
        if (!$this->exam_date || $this->is_completed) {
            return null;
        }

        return now()->diffForHumans($this->exam_date, true) . ' remaining';
    }

    public function getStatistics(): array
    {
        $results = $this->results;
        $total = $results->count();

        if (!$total) {
            return [
                'participation' => 0,
                'average' => 0,
                'highest' => 0,
                'lowest' => 0,
                'passing_rate' => 0,
                'grade_distribution' => []
            ];
        }

        $grades = [
            'A+' => 0, 'A' => 0, 'B' => 0,
            'C' => 0, 'D' => 0, 'F' => 0
        ];

        foreach ($results as $result) {
            $grades[$result->letter_grade]++;
        }

        return [
            'participation' => $total,
            'average' => $this->average_score,
            'highest' => $this->highest_score,
            'lowest' => $results->min('marks'),
            'passing_rate' => $this->passing_percentage,
            'grade_distribution' => array_map(
                fn($count) => round(($count / $total) * 100, 2),
                $grades
            )
        ];
    }

    public function getTopPerformers(int $limit = 3): array
    {
        return $this->results()
            ->with('student:id,name')
            ->orderByDesc('marks')
            ->take($limit)
            ->get()
            ->map(fn($result) => [
                'student_name' => $result->student->name,
                'marks' => $result->marks,
                'percentage' => round(($result->marks / $this->total_marks) * 100, 2)
            ])
            ->toArray();
    }
}
