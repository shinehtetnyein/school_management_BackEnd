<?php

namespace Modules\Homework\app\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\User\app\Models\User;

class StudentHomework extends Model
{
    protected $table = 'student_homeworks';

    protected $fillable = ['user_id', 'homework_id', 'submitted_at', 'file_url', 'marks', 'remarks'];

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
