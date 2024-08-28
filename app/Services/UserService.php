<?php

namespace App\Services;

use App\Enum\FollowStatus;
use App\Models\Following;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public function checkFollowingStatus($id){
       $isCurrentAuthFollowingUser= Following::where('id_followed_user', $id)
            ->where('id_follower', Auth::id())
            ->exists();
       if($isCurrentAuthFollowingUser){
           return FollowStatus::FOLLOWING;
       }
       $isCurrentAuthBeingFollowed=Following::where('id_followed_user', Auth::id())
           ->where('id_follower', $id)
           ->exists();
       if($isCurrentAuthBeingFollowed){
           return FollowStatus::FOLLOWING_ME;
       }
       return FollowStatus::NOT_FOLLOWING;

    }

    public function followAction($id){
        $isCurrentAuthFollowingUser= Following::where('id_followed_user', $id)
            ->where('id_follower', Auth::id())
            ->exists();
        if($isCurrentAuthFollowingUser){
            Following::where('id_followed_user',$id)
                ->where('id_follower', Auth::id())
                ->delete();
            Log::info('User ' . Auth::user()->username . ' unfollowed user with user_id ' . $id);

            return;
        }
        Following::insert([
            'id_followed_user' => $id,
            'id_follower' => Auth::id(),
        ]);
        Log::info('User ' . Auth::user()->username . ' followed user with user_id ' . $id);

    }
}
