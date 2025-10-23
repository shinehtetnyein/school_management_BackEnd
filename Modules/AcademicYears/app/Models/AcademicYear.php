<?php

namespace Modules\AcademicYears\app\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\AcademicYears\app\Models\AcademicDetail;
use Modules\Users\User\Models\User;

class AcademicYear extends Model
{
    protected $fillable = ['year_name', 'start_date', 'end_date', 'is_current', 'status', 'description', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    public function academicDetails()
    {
        return $this->hasMany(AcademicDetail::class, 'academic_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
