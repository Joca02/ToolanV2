<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function filterUsers($name){
        $users = User::where('name', 'LIKE', $name . '%')
            ->where('user_type', '!=', 'admin')
            ->get();

        return response()->json($users);
    }

    public function getUser($id){
        return User::where('id_user', $id)->first();
    }
}
