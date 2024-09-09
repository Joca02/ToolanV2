<?php

namespace App\Services;

use App\Models\Ban;
use App\Models\User;

class AdminService
{
    public function getUserBanStatus($userId){
       $isBanned= Ban::where('id_user',$userId)->exists();
       if($isBanned){
           $response="banned";
       }
       else{
           $response="not banned";
       }
       return response()->json($response);
    }

    public function banUser($userId,$banDate,$banReason){
        try {

            $user = User::find($userId);
            if (!$user) {
                return response()->json(['failure' => 'User not found.'], 404);
            }

            Ban::create([
                'id_user' => $userId,
                'date_end' => $banDate,
                'ban_reason' => $banReason,
            ]);

            return response()->json('success' );
        } catch (\Exception $e) {
            return response()->json(['failure' => 'An error occurred while banning the user.'], 500);
        }
    }

    public function unbanUser($userId){
        try {
            $user = User::find($userId);
            if (!$user) {
                return response()->json(['failure' => 'User not found.'], 404);
            }
            Ban::where('id_user',$userId)->delete();
            return response()->json('success' );
        } catch (\Exception $e) {
            return response()->json(['failure' => 'An error occurred while banning the user.'], 500);
        }
    }

    public function getBannedUsers()
    {
        $bannedUsers = Ban::join('users', 'bans.id_user', '=', 'users.id_user')
            ->select('users.id_user', 'users.name', 'users.profile_picture', 'bans.date_end', 'bans.ban_reason')
            ->where('bans.date_end', '>', now())
            ->get();

        return response()->json($bannedUsers);
    }
}
