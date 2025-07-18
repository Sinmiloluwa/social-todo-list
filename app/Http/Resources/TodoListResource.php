<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'items' => TodoItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
