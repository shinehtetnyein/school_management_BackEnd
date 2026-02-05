<?php

namespace Modules\Attendance\app\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Course\app\Models\Course;
use Modules\Users\User\app\Models\User;

class Attendance extends Model
{
    protected $fillable = ['date', 'status', 'verification_method', 'user_id', 'course_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
