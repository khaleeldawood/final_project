<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\EventRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Carbon;

class EventRequestService
{
    public function __construct(private GamificationService $gamificationService)
    {
    }

    public function createRequest(int $eventId, User $user, string $role): EventRequest
    {
        $event = Event::with('createdBy')->findOrFail($eventId);

        $alreadyRequested = EventRequest::where('event_id', $eventId)
            ->where('user_id', $user->id)
            ->exists();
        if ($alreadyRequested) {
            throw new \RuntimeException('You have already requested to join this event');
        }

        $alreadyParticipating = EventParticipant::where('event_id', $eventId)
            ->where('user_id', $user->id)
            ->exists();
        if ($alreadyParticipating) {
            throw new \RuntimeException('You are already participating in this event');
        }

        $request = EventRequest::create([
            'event_id' => $eventId,
            'user_id' => $user->id,
            'requested_role' => $role,
            'status' => 'PENDING',
        ]);

        Notification::create([
            'user_id' => $event->createdBy?->id,
            'message' => "{$user->name} requested to join '{$event->title}' as {$role}",
            'type' => 'EVENT_UPDATE',
            'link_url' => "/events/{$eventId}",
            'is_read' => false,
        ]);

        return $request;
    }

    public function acceptRequest(int $requestId): void
    {
        $request = EventRequest::with(['event', 'user'])->findOrFail($requestId);
        if ($request->status !== 'PENDING') {
            throw new \RuntimeException('Request has already been processed');
        }

        $event = $request->event;
        $user = $request->user;
        $role = $request->requested_role;

        $points = match ($role) {
            'ORGANIZER' => $event->organizer_points ?? 50,
            'VOLUNTEER' => $event->volunteer_points ?? 20,
            'ATTENDEE' => $event->attendee_points ?? 10,
            default => 0,
        };

        EventParticipant::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'role' => $role,
            'points_awarded' => $points,
            'joined_at' => Carbon::now(),
        ]);

        $this->gamificationService->awardPoints(
            $user,
            $points,
            'EVENT',
            $event->id,
            "Joined event '{$event->title}' as {$role}"
        );

        $request->status = 'ACCEPTED';
        $request->save();

        Notification::create([
            'user_id' => $user->id,
            'message' => "Your request to join '{$event->title}' as {$role} was accepted!",
            'type' => 'EVENT_UPDATE',
            'link_url' => "/events/{$event->id}",
            'is_read' => false,
        ]);
    }

    public function rejectRequest(int $requestId, string $reason): void
    {
        $request = EventRequest::with(['event', 'user'])->findOrFail($requestId);
        if ($request->status !== 'PENDING') {
            throw new \RuntimeException('Request has already been processed');
        }

        $request->status = 'REJECTED';
        $request->save();

        Notification::create([
            'user_id' => $request->user_id,
            'message' => "Your request to join '{$request->event->title}' was rejected. Reason: {$reason}",
            'type' => 'EVENT_UPDATE',
            'link_url' => "/events/{$request->event->id}",
            'is_read' => false,
        ]);
    }

    public function getPendingRequestsForEvent(int $eventId): array
    {
        return EventRequest::with(['event', 'user.university'])
            ->where('event_id', $eventId)
            ->where('status', 'PENDING')
            ->orderByDesc('created_at')
            ->get()
            ->values()
            ->all();
    }

    public function getPendingRequestsForUser(int $userId): array
    {
        return EventRequest::with(['event', 'user.university'])
            ->where('user_id', $userId)
            ->where('status', 'PENDING')
            ->orderByDesc('created_at')
            ->get()
            ->values()
            ->all();
    }

    public function getMyPendingRequests(int $creatorId): array
    {
        return EventRequest::with(['event', 'user.university'])
            ->whereHas('event', fn ($query) => $query->where('created_by', $creatorId))
            ->where('status', 'PENDING')
            ->orderByDesc('created_at')
            ->get()
            ->values()
            ->all();
    }
}
