<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    public function showHomePage(){
        return response()
            ->view('admin.admin_home')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function showProfile(Request $request){
        $admin=Auth::user();
        $userProfile=$this->userService->getUser($request->id);
        return response()->view(
            'admin.admin_profile',
            compact('userProfile', 'admin')
        )->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }


}
