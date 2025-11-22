<?php

namespace Modules\ClassRoom\app\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $this is expected to be a TimeTable model instance or an array with keys
        return [
            'id' => $this->id ?? null,
            'course_name' => data_get($this, 'course.name') ?? data_get($this, 'course_name'),
            'subject_name' => data_get($this, 'subject.name') ?? data_get($this, 'subject_name'),
            'section_name' => data_get($this, 'section.name') ?? data_get($this, 'section_name'),
            'teacher_name' => data_get($this, 'teacher.name') ?? data_get($this, 'teacher_name'),
            'teacher_email' => data_get($this, 'teacher.email') ?? null,
            'room_name' => $this->room_number ?? data_get($this, 'room_name') ?? null,
            'day' => $this->day_of_week ?? data_get($this, 'day') ?? null,
            'start_time' => $this->formatTime(data_get($this, 'start_time') ?? $this->start_time ?? null),
            'end_time' => $this->formatTime(data_get($this, 'end_time') ?? $this->end_time ?? null),
            'notes' => $this->notes ?? data_get($this, 'notes') ?? null,
            'number_of_students_in_classroom' => data_get($this, 'number_of_students_in_classroom') ?? null,
            'number_of_students_in_section' => isset($this->section) ? ($this->section->students_count ?? data_get($this, 'number_of_students_in_section')) : data_get($this, 'number_of_students_in_section'),
        ];
    }

    /**
     * Format a time value (Carbon instance or time string) to H:i
     */
    protected function formatTime($value)
    {
        if (!$value) return null;

        // If it's a DateTime/Carbon instance
        if ($value instanceof \DateTimeInterface) {
            return $value->format('H:i');
        }

        // If it's a string like '08:00:00' or '08:00'
        try {
            return Carbon::parse($value)->format('H:i');
        } catch (\Exception $e) {
            return (string) $value;
        }
    }
}
