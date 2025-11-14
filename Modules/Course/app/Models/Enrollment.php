<?php
// Modules/Course/app/Models/Enrollment.php

namespace Modules\Course\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\User\app\Models\User;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'enrolled_at',
        'updated_at',
        'created_at',
        'status',
        'class_level'
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'updated_at' => 'datetime',
        'created_at' => 'datetime'
    ];

    /**
     * Get the status enum values
     */
    public static function getStatusOptions(): array
    {
        return ['active', 'completed', 'dropped'];
    }

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Scope for active enrollments
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for user enrollments
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
