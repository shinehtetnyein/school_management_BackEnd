<?php

namespace Modules\Users\User\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserApiResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->getRoleNames()->first(), // get the first assigned role
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
