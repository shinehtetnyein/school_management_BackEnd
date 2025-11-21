<?php

namespace Modules\TimeTable\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\ClassRoom\app\Models\ClassRoom;
use Modules\ClassRoom\app\Models\Section;
use Modules\Course\App\Models\Course;
use Modules\Subject\App\Models\Subject;
use Modules\Users\User\App\Models\User;

class TimeTable extends Model
{
    use HasFactory;

    protected $table = 'time_tables';

    protected $fillable = [
        'uuid',
        'classroom_id',
        'section_id',
        'course_id',
        'day_of_week',
        'start_time',
        'end_time',
        'subject_id',
        'teacher_id',
        'room_number',
        'notes',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'status' => 'string',
    ];

    // Constants for days of week
    const DAYS = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * Get the classroom associated with this timetable entry
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'classroom_id');
    }

    /**
     * Get the section associated with this timetable entry
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * Get the course associated with this timetable entry
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Get the subject associated with this timetable entry
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the teacher (user) associated with this timetable entry
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
