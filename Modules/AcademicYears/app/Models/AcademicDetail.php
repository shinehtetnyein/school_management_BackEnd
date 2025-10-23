<?php

namespace Modules\AcademicYears\app\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\User\Models\User;

class AcademicDetail extends Model
{
    protected $fillable = ['user_id', 'academic_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id');
    }
}
