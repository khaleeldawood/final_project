<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;

class LeaderboardService
{
    public function getMembersLeaderboard(string $scope, ?int $universityId): array
    {
        if (strtoupper($scope) === 'UNIVERSITY') {
            if (!$universityId) {
                throw new \InvalidArgumentException('University ID is required for UNIVERSITY scope');
            }
            return User::with(['university', 'currentBadge'])
                ->where('university_id', $universityId)
                ->orderByDesc('points')
                ->get()
                ->all();
        }

        return User::with(['university', 'currentBadge'])
            ->orderByDesc('points')
            ->get()
            ->all();
    }

    public function getEventsLeaderboard(string $scope, ?int $universityId): array
    {
        $query = Event::with(['createdBy', 'participants'])
            ->withCount('participants')
            ->orderByDesc('participants_count');

        if (strtoupper($scope) === 'UNIVERSITY') {
            if (!$universityId) {
                throw new \InvalidArgumentException('University ID is required for UNIVERSITY scope');
            }
            $query->where('university_id', $universityId);
        }

        return $query->get()->all();
    }

    public function getTopMembers(string $scope, ?int $universityId, int $limit): array
    {
        return array_slice($this->getMembersLeaderboard($scope, $universityId), 0, $limit);
    }

    public function getTopEvents(string $scope, ?int $universityId, int $limit): array
    {
        return array_slice($this->getEventsLeaderboard($scope, $universityId), 0, $limit);
    }

    public function getUserRank(int $userId, string $scope, ?int $universityId): int
    {
        $leaderboard = $this->getMembersLeaderboard($scope, $universityId);
        foreach ($leaderboard as $index => $user) {
            if ((int) $user->id === $userId) {
                return $index + 1;
            }
        }

        return -1;
    }
}
