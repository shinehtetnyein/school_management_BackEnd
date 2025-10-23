<?php

namespace Modules\ClassRoom\app\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['room_number', 'building', 'room_type'];
}
