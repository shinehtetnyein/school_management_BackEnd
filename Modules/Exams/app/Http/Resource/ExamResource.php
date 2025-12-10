<?php

namespace Modules\Exams\app\Http\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'course_id' => $this->course_id,
            'exam_date' => $this->exam_date,
            'duration' => $this->duration,
            'total_marks' => $this->total_marks,
            'passing_marks' => $this->passing_marks,
            'description' => $this->description,
            'status' => $this->status,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'statistics' => method_exists($this, 'getStatistics') ? $this->getStatistics() : null,
        ];
    }
}
