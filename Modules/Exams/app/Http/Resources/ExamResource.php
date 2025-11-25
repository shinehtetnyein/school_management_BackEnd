<?php

namespace Modules\Exams\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Course\app\Http\Resources\CourseResource;
use Modules\Results\app\Http\Resources\ResultResource;

class ExamResource extends JsonResource
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
            'exam_date' => $this->exam_date,
            'duration' => $this->duration,
            'total_marks' => $this->total_marks,
            'passing_marks' => $this->passing_marks,
            'description' => $this->description,
            'status' => $this->status,
            'type' => $this->type,
            'course' => new CourseResource($this->whenLoaded('course')),
            'results' => ResultResource::collection($this->whenLoaded('results')),
            'passing_percentage' => $this->when(isset($this->passing_percentage), $this->passing_percentage),
            'average_score' => $this->when(isset($this->average_score), $this->average_score),
            'highest_score' => $this->when(isset($this->highest_score), $this->highest_score),
            'is_completed' => $this->when(isset($this->is_completed), $this->is_completed),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
