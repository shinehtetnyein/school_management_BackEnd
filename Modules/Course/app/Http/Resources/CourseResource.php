<?php

namespace Modules\Course\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'course_name' => $this->course_name,
            'description' => $this->description,
            'category' => $this->category,
            'subjects_count' => $this->when(isset($this->subjects_count), $this->subjects_count),
            'students_count' => $this->when(isset($this->students_count), $this->students_count),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
