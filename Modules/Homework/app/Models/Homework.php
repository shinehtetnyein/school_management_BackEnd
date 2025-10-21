<?php

namespace Modules\Homework\Models;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    protected $table = 'homeworks';

    protected $fillable = ['course_id', 'title', 'description', 'due_date'];
}
