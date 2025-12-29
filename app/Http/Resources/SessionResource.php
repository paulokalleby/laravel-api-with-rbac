<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'device' => $this->name,
            'ip' => $this->ip_address,
            'location' => $this->location,
            'login_at' => $this->created_at,
            'last_used' => $this->last_used_at,
        ];
    }
}
