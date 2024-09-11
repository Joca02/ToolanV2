<?php

namespace App\Services;

use App\Enum\ActionType;
use App\Enum\DeactivatedUserProps;
use App\Enum\FollowStatus;
use App\Enum\LikeStatus;
use App\Models\Comment;
use App\Models\DeactivatedUser;
use App\Models\Following;
use App\Models\Like;
use App\Models\Post;
use App\Models\Statistics;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserService
{
    public function filterUsers($name){
        $users = User::where('name', 'LIKE', $name . '%')
            ->where('user_type', '!=', 'admin')
            ->leftJoin('deactivated_users', 'users.id_user', '=', 'deactivated_users.id_user')
            ->whereNull('deactivated_users.id_user')
            ->select('users.*')
            ->get();

        return response()->json($users);
    }

    public function getUser($id){
        $user = User::where('id_user', $id)->first();

        $isDeactivated = DeactivatedUser::where('id_user', $user->id_user)->exists();

        if ($isDeactivated) {
            $user->name = DeactivatedUserProps::NAME->value;
            $user->profile_picture = DeactivatedUserProps::PROFILE_PICTURE->value;
            $user->prof_description = DeactivatedUserProps::DESCRIPTION->value;
            $user->username = DeactivatedUserProps::USERNAME->value;
        }
        return $user;
    }

    public static function getUsersNameByEmail($email){
        return User::where('email', $email)->first()->name;
    }
    public function getFollowersCount($userId){
        return Following::where('id_followed_user', $userId)->count();
    }

    public function getFollowingCount($userId){
        return Following::where('id_follower', $userId)->count();
    }

    public function getPostCount($userId){
        return Post::where('id_user', $userId)->count();
    }

    public function getFollowingInfo($userId){
        $following = User::join('following', 'users.id_user', '=', 'following.id_followed_user')
            ->where('following.id_follower', $userId)
            ->select('users.id_user', 'users.name', 'users.profile_picture')
            ->get();

        return response()->json($following);
    }

    public function getFollowersInfo($userId){
        $followers = User::join('following', 'users.id_user', '=', 'following.id_follower')
            ->where('following.id_followed_user', $userId)
            ->select('users.id_user', 'users.name', 'users.profile_picture')
            ->get();
        return response()->json($followers);
    }

    public function deactivateAccount($userId){
        $token = Str::random(60);
        DeactivatedUser::create([
            'id_user' => $userId,
            'token' => $token
        ]);
        StatisticsService::insertAction(ActionType::DEACTIVATE);
    }

    public function reactivateAccount($token,$email){
        $user = User::where('email', $email)->first();
        if(!$user){
            return redirect()
                ->route('login')
                ->with('failure', 'Invalid email');
        }
        $deactivatedUserInfo=DeactivatedUser::where('id_user', $user->id_user)->first();
        if (!$deactivatedUserInfo || $deactivatedUserInfo->token !== $token) {
            return redirect()
                ->route('login')
                ->with('failure', 'Invalid token for account reactivation');
        }
        $deactivatedUserInfo->delete();
        StatisticsService::insertAction(ActionType::REACTIVATE);
        return redirect()
            ->route('login')
            ->with('success', 'You have successfully reactivated your account! You can now login.');
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
            StatisticsService::insertAction(ActionType::UNFOLLOW);
            return;
        }
        Following::insert([
            'id_followed_user' => $id,
            'id_follower' => Auth::id(),
        ]);
        Log::info('User ' . Auth::user()->username . ' followed user with user_id ' . $id);
        StatisticsService::insertAction(ActionType::FOLLOW);

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
        StatisticsService::insertAction(ActionType::LIKE);

        return LikeStatus::LIKED;
    }

    public function getLikes($postId){
        $likes = Like::where('id_post', $postId)
            ->join('users', 'likes.id_user', '=', 'users.id_user')
            ->select('users.id_user as id_user', 'users.name', 'users.profile_picture')
            ->get();

        $likesTransformed = $likes->map(function ($like) {
            $isDeactivated = DeactivatedUser::where('id_user', $like->id_user)->exists();
            if ($isDeactivated) {
                return [
                    'id_user' => $like->id_user,
                    'name' => DeactivatedUserProps::NAME->value,
                    'profile_picture' => DeactivatedUserProps::PROFILE_PICTURE->value
                ];
            }
            return [
                'id_user' => $like->id_user,
                'name' => $like->name,
                'profile_picture' => $like->profile_picture
            ];
        });

        return $likesTransformed;
    }

    public function postComment($postId,$comment)
    {
        $newComment=Comment::insert([
            'id_user' => Auth::id(),
            'id_post' => $postId,
            'comment' => $comment
        ]);
        if($newComment){
            StatisticsService::insertAction(ActionType::COMMENT);
            return "success";
        }
        else{
            return "error";
        }
    }

    public function getComments($postId)
    {
        $comments = Comment::join('users', 'comments.id_user', '=', 'users.id_user')
            ->where('comments.id_post', $postId)
            ->select('users.id_user', 'users.name', 'users.profile_picture', 'comments.comment')
            ->get();

        $users = [];
        $commentsList = [];

        foreach ($comments as $comment) {
            // Check if the user is deactivated
            $isDeactivated = DeactivatedUser::where('id_user', $comment->id_user)->exists();

            if ($isDeactivated) {
                $users[] = [
                    'id_user' => $comment->id_user,
                    'name' => DeactivatedUserProps::NAME->value,
                    'profile_picture' => DeactivatedUserProps::PROFILE_PICTURE->value
                ];
            } else {
                $users[] = [
                    'id_user' => $comment->id_user,
                    'name' => $comment->name,
                    'profile_picture' => $comment->profile_picture
                ];
            }

            $commentsList[] = $comment->comment;
        }

        return ['users' => $users, 'comments' => $commentsList];
    }

    public function updateProfile($name, $description, $profilePicture = null)
    {
        $user = Auth::user();
        $fileName = $user->profile_picture;

        if ($profilePicture) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'JPG'];
            $extension = $profilePicture->getClientOriginalExtension();

            if (!in_array($extension, $allowedExtensions) || $profilePicture->getSize() > 10 * 1024 * 1024) {
                return 'file_failure';
            }

            $fileName = 'uploads/profile_pictures/' . $user->id_user . '.' . $extension;
            $profilePicture->move(public_path('uploads/profile_pictures'), $user->id_user . '.' . $extension);
        }

        try {
            User::where('id_user', $user->id_user)
                ->update([
                    'name' => $name,
                    'prof_description' => $description,
                    'profile_picture' => $fileName
                ]);

            return 'success';
        } catch (Exception $e) {
            error_log("Exception caught in query: " . $e);
            return 'failure';
        }
    }
}
