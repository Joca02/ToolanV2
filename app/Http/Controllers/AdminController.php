<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use App\Services\StatisticsService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private $userService;
    private $adminService;
    private $statisticsService;
    public function __construct(
        UserService $userService,
        AdminService $adminService,
        StatisticsService $statisticsService
    ){
        $this->userService = $userService;
        $this->adminService = $adminService;
        $this->statisticsService = $statisticsService;
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

    public function getUserBanStatus(Request $request){
        return $this->adminService->getUserBanStatus($request->userId);
    }

    public function banUser(Request $request){
        return $this->adminService->banUser(
            $request->userId,
            $request->banDate,
            $request->banReason
        );
    }
    public function unBanUser(Request $request){
        return $this->adminService->unBanUser($request->userId);
    }

    public function getBannedUsers()
    {
        return $this->adminService->getBannedUsers();
    }

    public function getStatisticsPage(){
        return response()
            ->view('admin.statistics')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function getStatistics()
    {
        $data=$this->statisticsService->getData();
        return response()->json($data);
    }
}
