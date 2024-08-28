<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    public function index()
    {
        return response()
            ->view('home')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function filterUsers(Request $request){
         return $this->userService->filterUsers($request->name);
    }

    public function showProfile(Request $request){
        $currentUser=Auth::user();
        $userProfile=$this->userService->getUser($request->id);
        return view('profile', compact('userProfile', 'currentUser'));
    }

    public function checkFollowingStatus(Request $request){
       $followingStatus= $this->userService->checkFollowingStatus($request->id);
       return response()->json($followingStatus);
    }

    public function followAction(Request $request){
        $this->userService->followAction($request->id);
        return response()->noContent();
    }
}
