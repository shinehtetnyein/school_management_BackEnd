<?php

namespace Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';

    protected $fillable = ['user_id', 'course_id', 'date', 'status', 'remarks'];
}
