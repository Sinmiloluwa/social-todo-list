<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteUserRequest;
use App\Http\Resources\TodoListResource;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        $query = TodoList::with([
            'items' => function ($q) {
                $q->orderBy('created_at', 'desc');
            },
            'owner',
            'users'
        ]);

        if ($user->type === 'admin') {
            $query->where('owner_id', $user->id);
        } else {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->where('owner_id', '!=', $user->id);
        }

        $lists = $query->orderBy('created_at', 'desc')->paginate(10);

        return $this->okResponse('Todo lists retrieved', TodoListResource::collection($lists));
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);
        if (auth()->user()->type !== 'admin') {
            return $this->forbiddenResponse('You are not authorized to create todo lists');
        }

        $list = auth()->user()->todoLists()->create([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return $this->createdResponse('Todo list created', $list);
    }

    public function show(TodoList $todoList)
    {
        $list = $todoList->load(['items.creator', 'owner', 'users']);

        if (! $list->users->contains(auth()->id()) && $list->owner_id !== auth()->id()) {
            return $this->forbiddenResponse('You are not authorized to view this todo list');
        }

        return $this->okResponse('Todo list found', $list);
    }

    public function update(Request $request, TodoList $todoList)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($todoList->owner_id !== auth()->id()) {
            return $this->forbiddenResponse('You are not authorized to update this todo list');
        }

        $todoList->update($request->only(['title','description']));

        return $this->createdResponse(
            'Todo list updated',
            $todoList
        );
    }

    public function destroy(TodoList $todoList)
    {
        if ($todoList->owner_id !== auth()->id()) {
            return $this->forbiddenResponse('You are not authorized to delete this todo list');
        }

        $todoList->delete();

        return $this->okResponse('Todo list deleted');
    }

    public function invite(Request $request, TodoList $todoList, User $user)
    {
        $todoList->load('users');

        if ($todoList->users->contains($user->id)) {
            return $this->conflictResponse('User is already a member of this todo list');
        }

        $todoList->users()->attach($user->id);

        $user->notify(new \App\Notifications\TodoListInvitation($todoList));

        return $this->okResponse(
            'User invited to todo list',
            [
                'list' => $todoList,
                'user' => $user,
            ]
        );
    }
}
