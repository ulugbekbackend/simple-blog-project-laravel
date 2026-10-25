<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user('sanctum');

        return [
            'id' => $this->id,
            'body' => $this->body,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->whenLoaded('user')),
            // Frontend o'chirish tugmasini ko'rsatish-ko'rsatmasligini shundan biladi
            'can_delete' => $user ? $user->can('delete', $this->resource) : false,
        ];
    }
}
