<?php

namespace Modules\Courses\app\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Attendance\app\Models\Attendance;

class Course extends Model
{
    protected $fillable = ['course_name', 'description', 'category'];

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'course_id');
    }

    // public function enrollments()
    // {
    //     return $this->hasMany(Enrollment::class, 'course_id');
    // }

    // public function homework()
    // {
    //     return $this->hasMany(Homework::class, 'course_id');
    // }

    // public function courseSubjects()
    // {
    //     return $this->hasMany(CourseSubject::class, 'course_id');
    // }

    // public function teacherCourses()
    // {
    //     return $this->hasMany(TeacherCourse::class, 'course_id');
    // }
}
