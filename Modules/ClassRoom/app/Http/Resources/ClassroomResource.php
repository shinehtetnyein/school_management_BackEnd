<?php

namespace Modules\ClassRoom\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassRoomResource extends JsonResource
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
            'room_number' => $this->room_number,
            'building' => $this->building,
            'room_type' => $this->room_type,
            'sections' => SectionResource::collection($this->whenLoaded('sections')),
            'students_count' => $this->whenLoaded('students', $this->students_count),
        ];
    }
}