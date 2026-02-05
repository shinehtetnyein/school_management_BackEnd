<?php

namespace Modules\Attendance\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'date' => $this->date,
            'status' => $this->status,
            'user' => $this->whenLoaded('user'),
            'course' => $this->whenLoaded('course'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
