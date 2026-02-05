<?php

namespace Modules\Homework\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Course\app\Http\Resources\CourseResource;

class HomeworkResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'course' => new CourseResource($this->whenLoaded('course')),
            'submissions' => StudentHomeworkResource::collection($this->whenLoaded('submissions')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
