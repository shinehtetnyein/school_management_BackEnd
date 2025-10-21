<?php

namespace Modules\Subjects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Courses\Models\Course;
use Modules\Users\Models\User;
use Modules\Departments\Models\Department;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = [
        'name',
        'code',
        'description',
        'credit_hours',
        'department_id',
        'status'
    ];

    protected $casts = [
        'credit_hours' => 'integer',
        'status' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        // When a subject is deleted, cascade to courses
        static::deleting(function ($subject) {
            $subject->courses()->delete();
        });
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class)
            ->withTimestamps();
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subject_teacher', 'subject_id', 'teacher_id')
            ->withTimestamps()
            ->withPivot(['assigned_date', 'status']);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)
            ->withDefault(['name' => 'General']);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeWithActiveTeachers($query)
    {
        return $query->whereHas('teachers', function ($q) {
            $q->where('status', true);
        });
    }
}
