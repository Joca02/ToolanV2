<?php

namespace App\Http\Controllers;

use App\Enum\PostLoadType;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private $postService;
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function getPostsFromAllUsers(Request $request){
        return $this->postService->getPosts(
            PostLoadType::ALL,
            $request->offset,
            $request->limit
        );
    }

    public function getPostsFromFollowedUsers(Request $request){
        return $this->postService->getPosts(
            PostLoadType::HOME,
            $request->offset,
            $request->limit
        );
    }

    public function getPostsFromUser(Request $request,$userId){
        return $this->postService->getPosts(
            PostLoadType::PROFILE,
            $request->offset,
            $request->limit,
            $userId
        );
    }

}
