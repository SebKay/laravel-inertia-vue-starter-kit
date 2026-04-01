<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class UserResource extends JsonApiResource
{
    /**
     * @return array<string, mixed>
     */
    public function toAttributes(Request $request): array
    {
        return [
            'email' => $this->whenHas('email'),
            'name' => $this->whenHas('name'),
            'emailVerified' => $this->hasVerifiedEmail(),
            'can' => $this->all_permissions,
        ];
    }
}
