<?php
// Modules/Course/app/Models/CourseSubject.php

namespace Modules\Course\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseSubject extends Pivot
{
    use HasFactory;

    protected $table = 'course_subject';

    protected $fillable = [
        'course_id',
        'subject_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
}
