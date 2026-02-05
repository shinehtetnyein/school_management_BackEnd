<?php

namespace Modules\Homework\app\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Course\app\Models\Course;
use Modules\Users\User\app\Models\User;

class Homework extends Model
{
    protected $table = 'homeworks';

    protected $fillable = ['course_id', 'title', 'description', 'due_date'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions()
    {
        return $this->hasMany(StudentHomework::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'student_homeworks')->withTimestamps();
    }
}
