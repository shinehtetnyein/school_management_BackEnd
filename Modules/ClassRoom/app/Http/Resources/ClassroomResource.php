<?php

namespace Modules\ClassRoom\app\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // base classroom fields
        $classroom = [
            'id' => $this->id ?? null,
            'uuid' => $this->uuid ?? null,
            'room_number' => $this->room_number ?? data_get($this, 'room_number'),
            'building' => $this->building ?? data_get($this, 'building'),
            'room_type' => $this->room_type ?? data_get($this, 'room_type'),
            'created_at' => optional($this->created_at)->toDateTimeString() ?? null,
            'updated_at' => optional($this->updated_at)->toDateTimeString() ?? null,
        ];

        // counts
        $classroom['number_of_students'] = $this->students_count ?? $this->number_of_students ?? null;

        // courses: try relationship then fallback to provided data
        $courses = [];
        if ($this->relationLoaded('timetables') && $this->timetables) {
            foreach ($this->timetables as $tt) {
                $name = data_get($tt, 'course.course_name') ?? data_get($tt, 'course_name');
                if ($name) $courses[] = $name;
            }
        }
        $classroom['courses'] = array_values(array_unique(array_filter($courses)));

        // sections list with counts
        $sections = [];
        if ($this->relationLoaded('sections') && $this->sections) {
            foreach ($this->sections as $section) {
                $sections[] = [
                    'id' => $section->id ?? null,
                    'name' => $section->name ?? data_get($section, 'name'),
                    'students_count' => $section->students_count ?? null,
                ];
            }
        }
        $classroom['sections'] = $sections;

        // schedule: prefer relation 'timetables' or accessor 'schedule'
        $scheduleItems = null;
        if ($this->relationLoaded('timetables') && $this->timetables) {
            $scheduleItems = ScheduleResource::collection($this->timetables);
        } elseif (isset($this->schedule)) {
            $scheduleItems = ScheduleResource::collection(collect($this->schedule));
        }

        $classroom['schedule'] = $scheduleItems ?? [];

        // schedule_by_day grouping
        $grouped = [];
        $rawItems = [];
        if ($scheduleItems instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection) {
            $rawItems = $scheduleItems->resolve();
        } elseif (is_array($scheduleItems)) {
            $rawItems = $scheduleItems;
        }

        foreach ($rawItems as $item) {
            $day = $item['day'] ?? ($item['day_of_week'] ?? 'Unspecified');
            $grouped[$day][] = $item;
        }

        $classroom['schedule_by_day'] = $grouped;

        return $classroom;
    }
}
