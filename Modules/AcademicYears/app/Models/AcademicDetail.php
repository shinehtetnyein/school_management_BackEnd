<?php
// Modules/Academic/app/Models/AcademicDetail.php

namespace Modules\AcademicYears\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\User\App\Models\User;

class AcademicDetail extends Model
{
    use HasFactory;

    protected $table = 'academic_details';

    protected $fillable = [
        'user_id',
        'academic_id'
    ];

    /**
     * Relationship with user - without roles
     */
    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'email');
    }

    /**
     * Relationship with academic year
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id');
    }
}
