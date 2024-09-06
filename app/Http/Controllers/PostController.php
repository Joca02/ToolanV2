<?php

namespace App\Http\Controllers;

use App\Enum\PostLoadType;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    public function getPostsFromUser(Request $request){
        return $this->postService->getPosts(
            PostLoadType::PROFILE,
            $request->offset,
            $request->limit,
            $request->userId
        );
    }

    public function addPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_description' => 'required|string|max:255',
            'picturePost' => 'nullable|mimes:jpg,jpeg,png|max:10240', // max 10MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->postService->createPost($request->post_description, $request->picturePost);

        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('fail', 500);
        }
    }

    public function deletePost(Request $request)
    {
        $result=$this->postService->deletePost($request->postId);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('fail', 500);
        }
    }


}
