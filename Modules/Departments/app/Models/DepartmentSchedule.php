<?php

namespace Modules\Departments\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Subjects\Models\Subject;

class DepartmentSchedule extends Model
{
    const PERIOD_REGULAR = 'regular';
    const PERIOD_ASSEMBLY = 'assembly';
    const PERIOD_EXAM = 'exam';
    const PERIOD_ACTIVITY = 'activity';
    const PERIOD_HOMEROOM = 'homeroom';

    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_MAKEUP = 'makeup';

    protected $fillable = [
        'department_id',
        'subject_id',
        'teacher_id',
        'grade_level',
        'section',
        'day_of_week',
        'period_type',
        'period_number',
        'start_time',
        'end_time',
        'room',
        'status',
        'date',
        'substitution_teacher_id',
        'notes',
        'attendance_required'
    ];

    protected $casts = [
        'date' => 'datetime',
        'status' => 'string',
        'attendance_required' => 'boolean',
        'period_number' => 'integer'
    ];

    protected function getStartTimeAttribute($value): Carbon
    {
        return Carbon::parse($this->date->format('Y-m-d') . ' ' . $value);
    }

    protected function getEndTimeAttribute($value): Carbon
    {
        return Carbon::parse($this->date->format('Y-m-d') . ' ' . $value);
    }

    // Relationships
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    // Scopes
    public function scopeForDay(Builder $query, int $dayOfWeek): Builder
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    public function scopeForDate(Builder $query, Carbon $date): Builder
    {
        return $query->where('date', $date->format('Y-m-d'));
    }

    public function scopeForRoom(Builder $query, string $room): Builder
    {
        return $query->where('room', $room);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeCompletedOnSchedule(Builder $query): Builder
    {
        return $query->where('status', 'completed_on_schedule');
    }

    public function scopeDelayed(Builder $query): Builder
    {
        return $query->where('status', 'delayed');
    }

    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeScheduledBetween(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
    }

    // Helper Methods
    public function getDuration(): string
    {
        return $this->start_time->diffForHumans($this->end_time, true);
    }

    public function isOngoing(): bool
    {
        $now = now();
        return $now->between($this->start_time, $this->end_time);
    }

    public function hasStarted(): bool
    {
        return now()->greaterThan($this->start_time);
    }

    public function hasEnded(): bool
    {
        return now()->greaterThan($this->end_time);
    }

    public function markAsCompleted(): bool
    {
        $this->status = 'completed_on_schedule';
        return $this->save();
    }

    public function markAsDelayed(): bool
    {
        $this->status = 'delayed';
        return $this->save();
    }

    public function markAsCancelled(string $reason = null): bool
    {
        $this->status = 'cancelled';
        if ($reason) {
            $this->notes = $reason;
        }
        return $this->save();
    }

    public function reschedule(Carbon $newDate, ?string $newStartTime = null, ?string $newEndTime = null): bool
    {
        $this->date = $newDate;
        if ($newStartTime) {
            $this->start_time = $newStartTime;
        }
        if ($newEndTime) {
            $this->end_time = $newEndTime;
        }
        return $this->save();
    }

    public function getTimeUntilStart(): string
    {
        if ($this->hasStarted()) {
            return 'Class has started';
        }

        return $this->start_time->diffForHumans();
    }

    public function getRemainingTime(): ?string
    {
        if (!$this->isOngoing()) {
            return null;
        }

        return $this->end_time->diffForHumans();
    }
}
