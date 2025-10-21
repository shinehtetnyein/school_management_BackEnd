<?php

namespace Modules\Departments\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Event extends Model
{
    const TYPE_ACADEMIC = 'academic';      // Parent-teacher meetings, study groups
    const TYPE_ATHLETIC = 'athletic';      // Sports events, tryouts
    const TYPE_CULTURAL = 'cultural';      // Art shows, music performances
    const TYPE_ADMINISTRATIVE = 'admin';   // Staff meetings, training
    const TYPE_EXTRACURRICULAR = 'club';  // Club meetings, activities
    const TYPE_ASSEMBLY = 'assembly';      // School assemblies
    const TYPE_EXAM = 'exam';             // Tests, finals
    const TYPE_FIELD_TRIP = 'field_trip'; // Educational trips

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'status',
        'event_type',
        'grade_level',
        'max_participants',
        'requires_permission',
        'eventable_id',
        'eventable_type',
        'additional_info'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'string',
        'requires_permission' => 'boolean',
        'max_participants' => 'integer',
        'grade_level' => 'array',
        'additional_info' => 'array'
    ];

    public function eventable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes for filtering events
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_date', '>', now())
                    ->where('status', 'upcoming');
    }

    public function scopeOngoing(Builder $query): Builder
    {
        return $query->where('start_date', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    })
                    ->where('status', 'in_progress');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed')
                    ->orWhere(function ($q) {
                        $q->whereNotNull('end_date')
                          ->where('end_date', '<', now());
                    });
    }

    public function scopeBetweenDates(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start_date', '<=', $start)
                          ->where('end_date', '>=', $end);
                    });
    }

    public function scopeByLocation(Builder $query, string $location): Builder
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    // Helper methods
    public function isUpcoming(): bool
    {
        return $this->start_date > now() && $this->status === 'upcoming';
    }

    public function isOngoing(): bool
    {
        return $this->start_date <= now() &&
               ($this->end_date === null || $this->end_date >= now()) &&
               $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed' ||
               ($this->end_date !== null && $this->end_date < now());
    }

    public function getDuration(): string
    {
        if ($this->end_date === null) {
            return 'No end date set';
        }

        return $this->start_date->diffForHumans($this->end_date, true);
    }

    public function getTimeUntilStart(): string
    {
        if ($this->start_date <= now()) {
            return 'Event has started';
        }

        return $this->start_date->diffForHumans();
    }

    public function getRemainingTime(): ?string
    {
        if ($this->isCompleted()) {
            return 'Event completed';
        }

        if (!$this->isOngoing()) {
            return null;
        }

        if ($this->end_date === null) {
            return 'No end date set';
        }

        return $this->end_date->diffForHumans();
    }

    public function markAsCompleted(): bool
    {
        $this->status = 'completed';
        $this->end_date = $this->end_date ?? now();
        return $this->save();
    }

    public function markAsInProgress(): bool
    {
        if ($this->isCompleted()) {
            return false;
        }

        $this->status = 'in_progress';
        return $this->save();
    }
}
