<?php

namespace App\Events;

use App\Models\TodoItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TodoItemCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public TodoItem $todoItem)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('todolist.' . $this->todoItem->todo_list_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->todoItem->id,
            'description' => $this->todoItem->description,
            'created_at' => $this->todoItem->created_at,
            'created_by' => [
                'id' => $this->todoItem->creator->id,
                'username' => $this->todoItem->creator->username,
            ],
        ];
    }

    public function broadcastAs(): string
    {
        return 'item.created';
    }
}
