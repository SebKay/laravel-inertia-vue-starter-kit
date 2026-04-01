<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class UserResource extends JsonApiResource
{
    public function toId(Request $request): string
    {
        return (string) $this->getKey();
    }

    /**
     * @return array<string, mixed>
     */
    public function toAttributes(Request $request): array
    {
        return [
            'email' => $this->whenHas('email'),
            'name' => $this->whenHas('name'),
            'can' => $this->all_permissions,
        ];
    }
}
