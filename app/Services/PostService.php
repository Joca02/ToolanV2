<?php

namespace App\Services;

use App\Dto\PostDto;
use App\Enum\PostLoadType;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function getPosts(PostLoadType $postType,int $offset,int $limit=3,$userId=null){
        switch ($postType){
            case PostLoadType::ALL:
                return $this->getPostsFromAllUsers($offset,$limit);
            case PostLoadType::HOME:
                return $this->getPostsFromFollowedUsers($offset,$limit);
            case PostLoadType::PROFILE:
                return $this->getUserPosts($userId,$offset,$limit);
        }
    }

    private function getPostsFromAllUsers(int $offset, int $limit)
    {
        $posts = Post::join('users', 'users.id_user', '=', 'posts.id_user')
            ->select([
                'posts.id_post',
                'users.profile_picture',
                'users.username',
                'posts.post_description',
                'users.id_user',
                'posts.date',
                'posts.picture'
            ])
            ->orderBy('posts.id_post', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();

        $postsDto=$posts->map(function($post){ return new PostDto($post,Auth::id());});
        return response()->json($postsDto);
    }

    private function getPostsFromFollowedUsers(int $offset, int $limit){
        $posts = Post::join('users', 'users.id_user', '=', 'posts.id_user')
            ->join('following', 'following.id_followed_user', '=', 'users.id_user')
            ->where('following.id_follower', Auth::id())
            ->select([
                'posts.id_post',
                'users.profile_picture',
                'users.username',
                'posts.post_description',
                'users.id_user',
                'posts.date',
                'posts.picture'
            ])
            ->orderBy('posts.id_post', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();

        $postsDto = $posts->map(function ($post) {
            return new PostDto($post, Auth::id());
        });

        return response()->json($postsDto);
    }

    public function getUserPosts(int $userId, int $offset, int $limit){
        $posts = Post::where('id_user', $userId)
            ->orderBy('id_post', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();

        $postsDto = $posts->map(function ($post) {
            return new PostDto($post, Auth::id());
        });

        return response()->json($postsDto);
    }


}
