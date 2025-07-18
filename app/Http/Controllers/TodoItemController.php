<?php

namespace App\Http\Controllers;

use App\Events\TodoItemCreated;
use App\Http\Resources\TodoItemResource;
use App\Models\TodoItem;
use App\Models\TodoList;
use Illuminate\Http\Request;

class TodoItemController extends Controller
{
    public function create(Request $request, TodoList $todoList)
    {
        $request->validate(['description' => 'required|string']);

        if (! $todoList->users->contains(auth()->id()) && $todoList->owner_id !== auth()->id()) {
            return $this->forbiddenResponse('You are not authorized to add items to this todo list');
        }

        $item = $todoList->items()->create([
            'description' => $request->description,
            'created_by' => auth()->id(),
        ]);

        $item->load('creator');

        broadcast(new TodoItemCreated($item))->toOthers();

        return $this->createdResponse('Item created', new TodoItemResource($item));
    }

    public function update(Request $request, TodoItem $todoItem)
    {
        $todoItem->load('creator');

        if ($todoItem->created_by !== auth()->id()) {
            return $this->forbiddenResponse('You are not authorized to update this item');
        }

        $todoItem->update($request->only('description'));

        return $this->okResponse('Item updated', new TodoItemResource($todoItem));
    }

    public function destroy(TodoItem $todoItem)
    {
        $todoItem->load('creator');

        if ($todoItem->created_by !== auth()->id()) {
            return $this->forbiddenResponse('You are not authorized to delete this item');
        }

        $todoItem->delete();

        return $this->okResponse('Item deleted');
    }
}
