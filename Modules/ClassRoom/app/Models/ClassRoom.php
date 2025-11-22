<?php

namespace Modules\ClassRoom\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\TimeTable\app\Models\TimeTable;
use Modules\Users\User\App\Models\User;
use Illuminate\Support\Arr;

class Classroom extends Model
{
    protected $fillable = ['room_number', 'building', 'room_type'];

    /**
     * Automatically create sections A..E after a classroom is created (if none exist)
     */
    protected static function booted()
    {
        static::created(function (Classroom $classroom) {
            // Only create sections if classroom has none
            if ($classroom->sections()->count() === 0) {
                $letters = ['A', 'B', 'C', 'D', 'E'];
                $now = now();
                $rows = array_map(function ($letter) use ($classroom, $now) {
                    return [
                        'name' => 'Section ' . $letter,
                        'classroom_id' => $classroom->id,
                        'status' => 'active',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }, $letters);

                // Create sections via Eloquent so model events run
                foreach ($rows as $row) {
                    Section::create($row);
                }

                // Reload relation
                $classroom->load('sections');
            }
        });
    }

    /**
     * Sections that belong to this classroom
     */
    public function sections(): HasMany
    {
        // Ensure sections are returned ordered by name (Section A..E)
        return $this->hasMany(Section::class, 'classroom_id')->orderBy('name');
    }

    /**
     * Students assigned to this classroom (many-to-many)
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_student', 'classroom_id', 'user_id')->withTimestamps();
    }

    /**
     * Timetable entries for this classroom
     */
    public function timetables(): HasMany
    {
        return $this->hasMany(TimeTable::class, 'classroom_id');
    }

    /**
     * Accessor to get a friendly schedule structure used by the frontend
     */
    public function getScheduleAttribute()
    {
        return $this->timetables()->with(['course', 'subject', 'section', 'teacher'])->get()->map(function (TimeTable $t) {
            return [
                'id' => $t->id,
                'course_name' => optional($t->course)->name,
                'subject_name' => optional($t->subject)->name,
                'section_name' => optional($t->section)->name,
                'teacher_name' => optional($t->teacher)->name,
                'room_name' => $t->room_number ?? $this->room_number ?? null,
                'day' => $t->day_of_week,
                'start_time' => $t->start_time, // stored as time
                'end_time' => $t->end_time,
                'notes' => $t->notes ?? null,
                // number of students in the classroom (overall) and in the section (if available)
                'number_of_students_in_classroom' => $this->students()->count(),
                'number_of_students_in_section' => $t->section ? $t->section->students()->count() : null,
            ];
        })->toArray();
    }
}
