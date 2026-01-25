<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\EventParticipationRequest;
use App\Models\User;
use Illuminate\Support\Carbon;

class EventParticipationRequestService
{
    public function submitRequest(int $eventId, User $user, string $requestedRole): array
    {
        $event = Event::findOrFail($eventId);

        if (EventParticipant::where('event_id', $eventId)->where('user_id', $user->id)->exists()) {
            throw new \RuntimeException('You are already participating in this event');
        }

        $hasPending = EventParticipationRequest::where('event_id', $eventId)
            ->where('user_id', $user->id)
            ->where('status', 'PENDING')
            ->exists();
        if ($hasPending) {
            throw new \RuntimeException('You already have a pending request for this event');
        }

        $request = EventParticipationRequest::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'requested_role' => $requestedRole,
            'status' => 'PENDING',
            'requested_at' => Carbon::now(),
        ]);

        $request->load(['event', 'user', 'respondedBy']);

        return $this->mapToResponse($request);
    }

    public function getEventRequests(int $eventId): array
    {
        return EventParticipationRequest::with(['event', 'user', 'respondedBy'])
            ->where('event_id', $eventId)
            ->where('status', 'PENDING')
            ->orderByDesc('requested_at')
            ->get()
            ->map(fn (EventParticipationRequest $request) => $this->mapToResponse($request))
            ->values()
            ->all();
    }

    public function getUserRequests(User $user): array
    {
        return EventParticipationRequest::with(['event', 'user', 'respondedBy'])
            ->where('user_id', $user->id)
            ->orderByDesc('requested_at')
            ->get()
            ->map(fn (EventParticipationRequest $request) => $this->mapToResponse($request))
            ->values()
            ->all();
    }

    public function approveRequest(int $requestId, User $approver): void
    {
        $request = EventParticipationRequest::with(['event', 'user'])->findOrFail($requestId);
        if ($request->status !== 'PENDING') {
            throw new \RuntimeException('Request already processed');
        }

        $event = $request->event;
        $points = match ($request->requested_role) {
            'ORGANIZER' => $event->organizer_points,
            'VOLUNTEER' => $event->volunteer_points,
            'ATTENDEE' => $event->attendee_points,
            default => 0,
        };

        EventParticipant::create([
            'event_id' => $event->id,
            'user_id' => $request->user_id,
            'role' => $request->requested_role,
            'points_awarded' => $points,
            'joined_at' => Carbon::now(),
        ]);

        $request->status = 'APPROVED';
        $request->responded_at = Carbon::now();
        $request->responded_by = $approver->id;
        $request->save();
    }

    public function rejectRequest(int $requestId, User $rejecter): void
    {
        $request = EventParticipationRequest::findOrFail($requestId);
        if ($request->status !== 'PENDING') {
            throw new \RuntimeException('Request already processed');
        }

        $request->status = 'REJECTED';
        $request->responded_at = Carbon::now();
        $request->responded_by = $rejecter->id;
        $request->save();
    }

    private function mapToResponse(EventParticipationRequest $request): array
    {
        return [
            'requestId' => $request->id,
            'eventId' => $request->event?->id,
            'eventTitle' => $request->event?->title,
            'userId' => $request->user?->id,
            'userName' => $request->user?->name,
            'userEmail' => $request->user?->email,
            'requestedRole' => $request->requested_role,
            'status' => $request->status,
            'requestedAt' => $request->requested_at ? Carbon::parse($request->requested_at)->toIso8601String() : null,
            'respondedAt' => $request->responded_at ? Carbon::parse($request->responded_at)->toIso8601String() : null,
            'respondedByName' => $request->respondedBy?->name,
        ];
    }
}
