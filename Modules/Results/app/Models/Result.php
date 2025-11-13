<?php

namespace Modules\Results\app\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'student_id',
        'exam_id',
        'course_id',
        'marks',
        'grade',
        'status', // Pass/Fail
        'remarks',
        'date',
    ];

    // Relationships can be added here
}
