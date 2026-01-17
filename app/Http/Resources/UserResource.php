<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->whenHas('email'),
            'name' => $this->whenHas('name'),
            'can' => $this->all_permissions,
        ];
    }
}
