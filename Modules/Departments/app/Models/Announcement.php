<?php

namespace Modules\Departments\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Announcement extends Model
{
    const CATEGORY_ACADEMIC = 'academic';           // Homework, test dates
    const CATEGORY_ADMINISTRATIVE = 'administrative'; // School policies
    const CATEGORY_EVENTS = 'events';               // School events
    const CATEGORY_EMERGENCY = 'emergency';         // Urgent notifications
    const CATEGORY_EXTRACURRICULAR = 'extracurricular'; // Clubs, sports
    const CATEGORY_GENERAL = 'general';             // General information

    const AUDIENCE_ALL = 'all';
    const AUDIENCE_STUDENTS = 'students';
    const AUDIENCE_PARENTS = 'parents';
    const AUDIENCE_TEACHERS = 'teachers';
    const AUDIENCE_STAFF = 'staff';

    protected $fillable = [
        'title',
        'content',
        'publish_date',
        'expiry_date',
        'priority',
        'category',
        'target_audience',
        'grade_level',
        'requires_acknowledgment',
        'attachments',
        'announceable_id',
        'announceable_type'
    ];

    protected $casts = [
        'publish_date' => 'datetime',
        'expiry_date' => 'datetime',
        'priority' => 'integer',
        'grade_level' => 'array',
        'requires_acknowledgment' => 'boolean',
        'attachments' => 'array'
    ];

    public function announceable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes for filtering announcements
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('publish_date', '<=', now())
                ->where(function ($q) {
                    $q->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>', now());
                });
        });
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('publish_date', '>', now());
    }

    public function scopeByPriority(Builder $query, int $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    public function scopeHighPriority(Builder $query): Builder
    {
        return $query->where('priority', '>=', 8);
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->publish_date <= now() &&
               ($this->expiry_date === null || $this->expiry_date > now());
    }

    public function isExpired(): bool
    {
        return $this->expiry_date !== null && $this->expiry_date < now();
    }

    public function isUpcoming(): bool
    {
        return $this->publish_date > now();
    }

    public function getTimeUntilPublish(): string
    {
        if (!$this->isUpcoming()) {
            return 'Already published';
        }

        return $this->publish_date->diffForHumans();
    }

    public function getTimeUntilExpiry(): ?string
    {
        if ($this->expiry_date === null) {
            return 'No expiry date';
        }

        if ($this->isExpired()) {
            return 'Expired ' . $this->expiry_date->diffForHumans();
        }

        return 'Expires ' . $this->expiry_date->diffForHumans();
    }
}
