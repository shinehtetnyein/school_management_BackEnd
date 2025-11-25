<?php

namespace Modules\Results\app\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\User\app\Models\User;
use Modules\Exams\app\Models\Exam;
use Modules\Course\app\Models\Course;

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

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
