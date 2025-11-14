<?php
// Modules/Users/Admin/app/Http/Resource/AdminUserResource.php

namespace Modules\Users\Admin\App\Http\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_no' => $this->phone_no,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'status' => $this->status,
            'roles' => $this->roles->map(fn($role) => $role->name)->toArray(),
            'permissions' => $this->getAllPermissions()->map(fn($perm) => $perm->name)->toArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
