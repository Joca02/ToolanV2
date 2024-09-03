<?php

namespace App\Services;

use App\Dto\PostDto;
use App\Enum\PostLoadType;
use App\Models\Comment;
use App\Models\Like;
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

        $postsDto = $posts->map(function ($post) {
            $isLiked=$this->isPostLiked($post->id_post);
            $likesCount=$this->getLikesCount($post->id_post);
            $commentsCount=$this->getCommentsCount($post->id_post);
            return new PostDto($post, Auth::id(), $isLiked, $likesCount, $commentsCount);
        });
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
            $isLiked=$this->isPostLiked($post->id_post);
            $likesCount=$this->getLikesCount($post->id_post);
            $commentsCount=$this->getCommentsCount($post->id_post);
            return new PostDto($post, Auth::id(), $isLiked, $likesCount, $commentsCount);
        });

        return response()->json($postsDto);
    }

    public function getUserPosts(int $userId, int $offset, int $limit){
        $posts = Post::join('users', 'users.id_user', '=', 'posts.id_user')
            ->where('posts.id_user', $userId)
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
            $isLiked=$this->isPostLiked($post->id_post);
            $likesCount=$this->getLikesCount($post->id_post);
            $commentsCount=$this->getCommentsCount($post->id_post);
            return new PostDto($post, Auth::id(), $isLiked, $likesCount, $commentsCount);
        });

        return response()->json($postsDto);
    }

    private function isPostLiked($postId){
        return Like::where('id_post', $postId)
            ->where('id_user', Auth::id())
            ->exists();
    }

    private function getLikesCount($postId){
        return Like::where('id_post', $postId)->count();
    }

    private function getCommentsCount($postId){
        return Comment::where('id_post', $postId)->count();
    }

    public function createPost($description, $picture = null)
    {
        $userId = Auth::id();
        $fileName = null;

        if ($picture) {
            $extension = $picture->getClientOriginalExtension();

            $nextPostID =Post::max('id_post') + 1;
            $fileName = 'uploads/posts/' . $nextPostID . '.' . $extension;

            $picture->move(public_path('uploads/posts'), $nextPostID . '.' . $extension);
        }

        $post = Post::insert([
            'id_user' => $userId,
            'post_description' => $description,
            'picture' => $fileName,
            'date' => now(),
            'creation_time' => now(),
        ]);

        return $post;
    }


}
