<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\Notification;
use App\Models\PointsLog;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Support\Carbon;

class GamificationService
{
    public function awardPoints(User $user, int $points, string $sourceType, int $sourceId, string $description): void
    {
        $user->points = ($user->points ?? 0) + $points;
        $user->save();

        PointsLog::create([
            'user_id' => $user->id,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'points' => $points,
            'description' => $description,
        ]);

        $this->checkAndPromoteBadge($user);
    }

    public function deductPoints(User $user, int $points, string $sourceType, int $sourceId, string $description): void
    {
        $user->points = max(0, ($user->points ?? 0) - $points);
        $user->save();

        PointsLog::create([
            'user_id' => $user->id,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'points' => -$points,
            'description' => $description,
        ]);

        $this->checkAndDemoteBadge($user);
    }

    public function checkAndPromoteBadge(User $user): void
    {
        $newBadge = Badge::where('points_threshold', '<=', $user->points ?? 0)
            ->orderByDesc('points_threshold')
            ->first();

        if (!$newBadge) {
            return;
        }

        $currentBadge = $user->currentBadge;
        if ($currentBadge && $currentBadge->id === $newBadge->id) {
            return;
        }

        $user->current_badge_id = $newBadge->id;
        $user->save();

        if (!UserBadge::where('user_id', $user->id)->where('badge_id', $newBadge->id)->exists()) {
            UserBadge::create([
                'user_id' => $user->id,
                'badge_id' => $newBadge->id,
                'earned_at' => Carbon::now(),
            ]);
        }

        Notification::create([
            'user_id' => $user->id,
            'message' => "Congratulations! You've earned the {$newBadge->name} badge!",
            'type' => 'BADGE_EARNED',
            'link_url' => '/badges',
            'is_read' => false,
        ]);
    }

    public function checkAndDemoteBadge(User $user): void
    {
        $currentBadge = $user->currentBadge;
        if (!$currentBadge) {
            return;
        }

        if (($user->points ?? 0) >= $currentBadge->points_threshold) {
            return;
        }

        $newBadge = Badge::where('points_threshold', '<=', $user->points ?? 0)
            ->orderByDesc('points_threshold')
            ->first();

        if ($newBadge && $newBadge->id === $currentBadge->id) {
            return;
        }

        $user->current_badge_id = $newBadge?->id;
        $user->save();

        $badgeName = $newBadge ? $newBadge->name : 'Newcomer';

        Notification::create([
            'user_id' => $user->id,
            'message' => "Your badge has been updated to {$badgeName} due to point changes.",
            'type' => 'POINTS_UPDATE',
            'link_url' => '/badges',
            'is_read' => false,
        ]);
    }
}
