<?php
// Modules/Academic/app/Models/AcademicYear.php

namespace Modules\AcademicYears\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\User\App\Models\User;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'year_name',
        'start_date',
        'end_date',
        'is_current',
        'status',
        'description',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_current' => 'boolean'
    ];

    /**
     * Get the status enum values
     */
    public static function getStatusOptions(): array
    {
        return ['active', 'inactive', 'completed'];
    }

    /**
     * Relationship with users (academic details) - without roles
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'academic_details', 'academic_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Relationship with created by user - without roles
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->select('id', 'name', 'email');
    }

    /**
     * Relationship with updated by user - without roles
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->select('id', 'name', 'email');
    }

    /**
     * Scope for current academic year
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    /**
     * Scope for active academic years
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
