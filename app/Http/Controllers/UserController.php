<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $username = $request->query('username');
        $users = User::where('username', 'LIKE', "%{$username}%")
            ->select('id', 'username', 'name')
            ->get();

        return $this->okResponse('Users found', $users);
    }
}
