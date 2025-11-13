<?php
namespace Modules\Results\app\Http\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'exam_id' => $this->exam_id,
            'course_id' => $this->course_id,
            'marks' => $this->marks,
            'grade' => $this->grade,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'date' => $this->date,
        ];
    }
}
