<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'users' => RelationshipResource::collection(
                $this->whenLoaded('users')
            ),
            'permissions' => PermissionResource::collection(
                $this->whenLoaded('permissions')
            ),
        ];
    }
}
