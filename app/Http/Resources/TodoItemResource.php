<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoItemResource extends JsonResource
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
            'todo_list_id' => $this->todo_list_id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'created_by' => $this->creator->name,
        ];
    }
}
