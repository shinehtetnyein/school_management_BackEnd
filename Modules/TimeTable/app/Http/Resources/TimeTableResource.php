<?php

namespace Modules\TimeTable\app\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'classroom_id' => $this->classroom_id,
            'classroom' => $this->whenLoaded('classroom', [
                'id' => $this->classroom->id,
                'room_number' => $this->classroom->room_number,
            ]),
            'section_id' => $this->section_id,
            'section' => $this->whenLoaded('section', [
                'id' => $this->section->id,
                'name' => $this->section->name,
            ]),
            'course_id' => $this->course_id,
            'course' => $this->whenLoaded('course', [
                'id' => $this->course->id,
                'name' => $this->course->name,
            ]),
            'day_of_week' => $this->day_of_week,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'subject_id' => $this->subject_id,
            'subject' => $this->whenLoaded('subject', [
                'id' => $this->subject->id,
                'name' => $this->subject->name,
            ]),
            'teacher_id' => $this->teacher_id,
            'teacher' => $this->whenLoaded('teacher', [
                'id' => $this->teacher->id,
                'uuid' => $this->teacher->uuid,
                'first_name' => $this->teacher->first_name,
                'last_name' => $this->teacher->last_name,
                'email' => $this->teacher->email,
            ]),
            'room_number' => $this->room_number,
            'notes' => $this->notes,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
