<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('todolist.{listId}', function ($user, $listId) {
    return $user->todoLists()->where('id', $listId)->exists()
        || $user->ownedTodoLists()->where('id', $listId)->exists();
});
