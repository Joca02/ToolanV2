<?php

namespace App\Services;

use App\Enum\FollowStatus;
use App\Enum\LikeStatus;
use App\Models\Comment;
use App\Models\Following;
use App\Models\Like;
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

    public function likeAction($postId){
        $like= Like::where('id_post', $postId)->where('id_user', Auth::id())->first();
        if($like){
            $like->delete();
            return LikeStatus::NOT_LIKED;
        }
        Like::create([
            'id_post' => $postId,
            'id_user' => Auth::id(),
        ]);
        return LikeStatus::LIKED;
    }

    public function getLikes($postId){
         return Like::where('id_post', $postId)
            ->join('users', 'likes.id_user', '=', 'users.id_user')
            ->select('users.id_user as id_user', 'users.name', 'users.profile_picture')
            ->get();
    }

    public function postComment($postId,$comment)
    {
        $newComment=Comment::insert([
            'id_user' => Auth::id(),
            'id_post' => $postId,
            'comment' => $comment
        ]);
        if($newComment){
            return "success";
        }
        else{
            return "error";
        }
    }

    public function getComments($postId){
        $comments=Comment::join('users', 'comments.id_user', '=', 'users.id_user')
            ->where('comments.id_post', $postId)
            ->select('users.id_user', 'users.name', 'users.profile_picture', 'comments.comment')
            ->get();
        $users = [];
        $commentsList = [];

        foreach ($comments as $comment) {
            $users[] = [
                'id_user' => $comment->id_user,
                'name' => $comment->name,
                'profile_picture' => $comment->profile_picture
            ];
            $commentsList[] = $comment->comment;
        }

        return ['users' => $users, 'comments' => $commentsList];
    }
}
