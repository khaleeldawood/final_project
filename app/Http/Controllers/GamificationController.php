<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;
use App\Services\LeaderboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GamificationController extends Controller
{
    public function __construct(private LeaderboardService $leaderboardService)
    {
    }

    public function leaderboard(Request $request): JsonResponse
    {
        $scope = $request->query('scope', 'GLOBAL');
        $type = $request->query('type', 'MEMBERS');
        $universityId = $request->query('universityId');
        $universityId = $universityId ? (int) $universityId : null;

        $response = [
            'scope' => $scope,
            'type' => $type,
        ];

        if (strtoupper($type) === 'MEMBERS') {
            $users = $this->leaderboardService->getMembersLeaderboard($scope, $universityId);
            $response['rankings'] = array_map(fn (User $user) => $this->mapUser($user), $users);
        } elseif (strtoupper($type) === 'EVENTS') {
            $events = $this->leaderboardService->getEventsLeaderboard($scope, $universityId);
            $response['rankings'] = array_map(fn ($event) => $this->mapEvent($event), $events);
        } else {
            return response()->json(['message' => 'Invalid leaderboard type. Use MEMBERS or EVENTS'], 400);
        }

        return response()->json($response);
    }

    public function topMembers(Request $request): JsonResponse
    {
        $scope = $request->query('scope', 'GLOBAL');
        $limit = (int) $request->query('limit', 10);
        $universityId = $request->query('universityId');
        $universityId = $universityId ? (int) $universityId : null;

        $users = $this->leaderboardService->getTopMembers($scope, $universityId, $limit);
        return response()->json(array_map(fn (User $user) => $this->mapUser($user), $users));
    }

    public function topEvents(Request $request): JsonResponse
    {
        $scope = $request->query('scope', 'GLOBAL');
        $limit = (int) $request->query('limit', 10);
        $universityId = $request->query('universityId');
        $universityId = $universityId ? (int) $universityId : null;

        $events = $this->leaderboardService->getTopEvents($scope, $universityId, $limit);
        return response()->json(array_map(fn ($event) => $this->mapEvent($event), $events));
    }

    public function myRank(Request $request): JsonResponse
    {
        $scope = $request->query('scope', 'GLOBAL');
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $universityId = strtoupper($scope) === 'UNIVERSITY' ? $user->university_id : null;
        $rank = $this->leaderboardService->getUserRank($user->id, $scope, $universityId);

        return response()->json([
            'rank' => $rank,
            'scope' => $scope,
            'points' => $user->points,
        ]);
    }

    public function userRank(Request $request, int $userId): JsonResponse
    {
        $scope = $request->query('scope', 'GLOBAL');
        $user = User::findOrFail($userId);
        $universityId = strtoupper($scope) === 'UNIVERSITY' ? $user->university_id : null;
        $rank = $this->leaderboardService->getUserRank($userId, $scope, $universityId);

        return response()->json([
            'rank' => $rank,
            'scope' => $scope,
            'points' => $user->points,
        ]);
    }

    public function badges(): JsonResponse
    {
        $badges = Badge::orderBy('points_threshold')->get()->all();
        return response()->json(array_map(fn (Badge $badge) => $this->mapBadge($badge), $badges));
    }

    public function myBadges(Request $request): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $allBadges = Badge::orderBy('points_threshold')->get()->all();
        $earned = UserBadge::with('badge')->where('user_id', $user->id)->get()->all();

        return response()->json([
            'allBadges' => array_map(fn (Badge $badge) => $this->mapBadge($badge), $allBadges),
            'earnedBadges' => $earned,
            'currentPoints' => $user->points ?? 0,
            'currentBadge' => $user->currentBadge ? $this->mapBadge($user->currentBadge) : null,
        ]);
    }

    private function mapUser(User $user): array
    {
        return [
            'userId' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'points' => $user->points ?? 0,
            'university' => $user->university ? [
                'universityId' => $user->university->id,
                'name' => $user->university->name,
            ] : null,
            'currentBadge' => $user->currentBadge ? $this->mapBadge($user->currentBadge) : null,
        ];
    }

    private function mapEvent($event): array
    {
        return [
            'eventId' => $event->id,
            'title' => $event->title,
            'type' => $event->type,
            'participants' => $event->participants ?? [],
            'creator' => $event->createdBy ? [
                'userId' => $event->createdBy->id,
                'name' => $event->createdBy->name,
                'email' => $event->createdBy->email,
            ] : null,
        ];
    }

    private function mapBadge(Badge $badge): array
    {
        return [
            'badgeId' => $badge->id,
            'name' => $badge->name,
            'description' => $badge->description,
            'pointsThreshold' => $badge->points_threshold,
        ];
    }

    private function getSessionUser(Request $request): ?User
    {
        $userId = $request->session()->get('user_id');
        if (!$userId) {
            return null;
        }

        return User::find($userId);
    }
}
