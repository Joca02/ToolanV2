<?php

namespace App\Services;

use App\Enum\ActionType;
use App\Models\Statistics;
use Carbon\Carbon;

class StatisticsService
{

    public static function insertAction(ActionType $actionType)
    {
        Statistics::insert([
            'action' => $actionType->value
        ]);
    }

    public function getData()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        $data = [];

        foreach (ActionType::cases() as $actionType) {
            $actionLabel = $this->actionDisplayNames[$actionType->value];
            $data[$actionLabel] = $this->getStatisticsForAction($actionType->value, $today, $startOfWeek, $startOfMonth, $startOfYear);
        }

        return $data;
    }

    private function getStatisticsForAction($action, $today, $startOfWeek, $startOfMonth, $startOfYear)
    {
        $total = Statistics::where('action', $action)->count();
        $todayCount = Statistics::where('action', $action)->where('time', '>=', $today)->count();
        $thisWeekCount = Statistics::where('action', $action)->where('time', '>=', $startOfWeek)->count();
        $thisMonthCount = Statistics::where('action', $action)->where('time', '>=', $startOfMonth)->count();
        $thisYearCount = Statistics::where('action', $action)->where('time', '>=', $startOfYear)->count();
        $totalAverage = $total / 12;

        return [
            'today' => $todayCount,
            'thisWeek' => $thisWeekCount,
            'thisMonth' => $thisMonthCount,
            'thisYear' => $thisYearCount,
            'totalCount' => $total,
            'totalAverage' => round($totalAverage, 2),
        ];
    }

    private $actionDisplayNames = [
        'follow' => 'Followings',
        'unfollow' => 'Unfollowings',
        'post_create' => 'Post creations',
        'post_delete' => 'Post deletions',
        'like' => 'Likes',
        'comment' => 'Comments',
        'deactivate' => 'Account Deactivations',
        'reactivate' => 'Account Reactivations',
        'register' => 'Registrations',
        'login' => 'Logins',
    ];

}
