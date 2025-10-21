<?php

namespace Modules\Homework\Models;

use Illuminate\Database\Eloquent\Model;

class StudentHomework extends Model
{
    protected $table = 'student_homeworks';

    protected $fillable = ['user_id', 'homework_id', 'submitted_at', 'file_url', 'marks', 'remarks'];
}
