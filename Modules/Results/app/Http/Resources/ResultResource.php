<?php

namespace Modules\Results\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Users\User\app\Http\Resources\UserApiResource;

class ResultResource extends JsonResource
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
            'marks' => $this->marks,
            'status' => $this->status,
            'student' => new UserApiResource($this->whenLoaded('student')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
