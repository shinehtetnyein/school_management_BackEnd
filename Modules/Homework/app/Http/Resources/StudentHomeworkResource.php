<?php

namespace Modules\Homework\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Users\User\app\Http\Resources\UserApiResource;

class StudentHomeworkResource extends JsonResource
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
            'submitted_at' => $this->submitted_at,
            'file_url' => $this->file_url,
            'marks' => $this->marks,
            'remarks' => $this->remarks,
            'student' => new UserApiResource($this->whenLoaded('student')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
