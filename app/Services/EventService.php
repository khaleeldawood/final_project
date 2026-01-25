<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\EventReport;
use App\Models\Notification;
use App\Models\University;
use App\Models\User;
use Illuminate\Support\Carbon;

class EventService
{
    public function __construct(private GamificationService $gamificationService)
    {
    }

    public function createEvent(array $data, User $creator): Event
    {
        $event = new Event();
        $event->title = $data['title'];
        $event->description = $data['description'];
        $event->location = $data['location'];
        $event->start_date = $data['startDate'];
        $event->end_date = $data['endDate'];
        $event->type = $data['type'];
        $event->status = 'PENDING';
        $event->created_by = $creator->id;

        if (!empty($data['universityId'])) {
            $university = University::findOrFail($data['universityId']);
            $event->university_id = $university->id;
        } else {
            $event->university_id = $creator->university_id;
        }

        $event->max_organizers = $data['maxOrganizers'] ?? null;
        $event->max_volunteers = $data['maxVolunteers'] ?? null;
        $event->max_attendees = $data['maxAttendees'] ?? null;
        $event->organizer_points = $data['organizerPoints'] ?? 50;
        $event->volunteer_points = $data['volunteerPoints'] ?? 20;
        $event->attendee_points = $data['attendeePoints'] ?? 10;
        $event->save();

        if (!empty($data['creatorParticipates']) && !empty($data['creatorRole'])) {
            $role = $data['creatorRole'];
            if (in_array($role, ['ORGANIZER', 'VOLUNTEER', 'ATTENDEE'], true)) {
                EventParticipant::create([
                    'event_id' => $event->id,
                    'user_id' => $creator->id,
                    'role' => $role,
                    'points_awarded' => 0,
                    'joined_at' => Carbon::now(),
                ]);
            }
        }

        return $event;
    }

    public function updateEvent(Event $event, array $data, User $currentUser): Event
    {
        if ($event->created_by !== $currentUser->id) {
            throw new \RuntimeException('You do not have permission to edit this event');
        }

        if (!in_array($event->status, ['PENDING', 'APPROVED'], true)) {
            throw new \RuntimeException('Only pending or approved events can be edited');
        }

        $wasApproved = $event->status === 'APPROVED';
        if ($wasApproved) {
            $event->status = 'PENDING';
        }

        $event->title = $data['title'];
        $event->description = $data['description'];
        $event->location = $data['location'];
        $event->start_date = $data['startDate'];
        $event->end_date = $data['endDate'];
        $event->type = $data['type'];
        $event->max_organizers = $data['maxOrganizers'] ?? null;
        $event->max_volunteers = $data['maxVolunteers'] ?? null;
        $event->max_attendees = $data['maxAttendees'] ?? null;
        $event->organizer_points = $data['organizerPoints'] ?? 50;
        $event->volunteer_points = $data['volunteerPoints'] ?? 20;
        $event->attendee_points = $data['attendeePoints'] ?? 10;
        $event->save();

        if ($wasApproved) {
            Notification::create([
                'user_id' => $currentUser->id,
                'message' => "Your event '{$event->title}' has been updated and requires re-approval",
                'type' => 'EVENT_UPDATE',
                'link_url' => "/events/{$event->id}",
                'is_read' => false,
            ]);
        }

        return $event;
    }

    public function joinEvent(Event $event, User $user, string $role): void
    {
        if ($event->status !== 'APPROVED') {
            throw new \RuntimeException('Cannot join an event that is not approved');
        }

        if (EventParticipant::where('event_id', $event->id)->where('user_id', $user->id)->exists()) {
            throw new \RuntimeException('You have already joined this event');
        }

        $maxCapacity = match ($role) {
            'ORGANIZER' => $event->max_organizers,
            'VOLUNTEER' => $event->max_volunteers,
            'ATTENDEE' => $event->max_attendees,
            default => null,
        };

        $currentCount = EventParticipant::where('event_id', $event->id)->where('role', $role)->count();
        if ($maxCapacity !== null && $currentCount >= $maxCapacity) {
            throw new \RuntimeException("No more slots available for {$role} role");
        }

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
            'EVENT_PARTICIPATION',
            $event->id,
            "Joined event '{$event->title}' as {$role}"
        );
    }

    public function leaveEvent(Event $event, User $user): void
    {
        $participant = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$participant) {
            throw new \RuntimeException('You are not participating in this event');
        }

        $pointsAwarded = $participant->points_awarded ?? 0;
        $penalty = $pointsAwarded * 2;
        $participant->delete();

        if ($penalty > 0) {
            $this->gamificationService->deductPoints(
                $user,
                $penalty,
                'EVENT_LEAVE',
                $event->id,
                "Left event '{$event->title}' (penalty applied)"
            );
        }

        Notification::create([
            'user_id' => $user->id,
            'message' => $penalty > 0
                ? "You left event '{$event->title}'. Penalty: -{$penalty} points"
                : "You left event '{$event->title}'",
            'type' => 'POINTS_UPDATE',
            'link_url' => "/events/{$event->id}",
            'is_read' => false,
        ]);
    }

    public function approveEvent(Event $event): void
    {
        if ($event->status === 'APPROVED') {
            throw new \RuntimeException('Event is already approved');
        }

        $event->status = 'APPROVED';
        $event->save();

        $creator = $event->createdBy;
        $creationPoints = $creator->role === 'STUDENT' ? 30 : 50;
        $this->gamificationService->awardPoints(
            $creator,
            $creationPoints,
            'EVENT_CREATION',
            $event->id,
            "Event approved: {$event->title}"
        );

        $participant = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $creator->id)
            ->first();

        if ($participant && ($participant->points_awarded ?? 0) === 0) {
            $participationPoints = match ($participant->role) {
                'ORGANIZER' => $event->organizer_points ?? 50,
                'VOLUNTEER' => $event->volunteer_points ?? 20,
                'ATTENDEE' => $event->attendee_points ?? 10,
                default => 0,
            };

            $participant->points_awarded = $participationPoints;
            $participant->save();

            $this->gamificationService->awardPoints(
                $creator,
                $participationPoints,
                'EVENT_PARTICIPATION',
                $event->id,
                "Joined event '{$event->title}' as {$participant->role}"
            );
        }

        Notification::create([
            'user_id' => $creator->id,
            'message' => "Your event '{$event->title}' has been approved!",
            'type' => 'EVENT_UPDATE',
            'link_url' => "/events/{$event->id}",
            'is_read' => false,
        ]);
    }

    public function rejectEvent(Event $event, string $reason): void
    {
        $event->status = 'CANCELLED';
        $event->save();

        Notification::create([
            'user_id' => $event->createdBy->id,
            'message' => "Your event '{$event->title}' was rejected. Reason: {$reason}",
            'type' => 'EVENT_UPDATE',
            'link_url' => "/events/{$event->id}",
            'is_read' => false,
        ]);
    }

    public function cancelEvent(Event $event, string $reason): void
    {
        $event->status = 'CANCELLED';
        $event->save();

        $participants = EventParticipant::where('event_id', $event->id)->get();
        foreach ($participants as $participant) {
            Notification::create([
                'user_id' => $participant->user_id,
                'message' => "Event '{$event->title}' has been cancelled. Reason: {$reason}",
                'type' => 'EVENT_UPDATE',
                'link_url' => "/events/{$event->id}",
                'is_read' => false,
            ]);
        }
    }

    public function deleteEvent(Event $event, User $currentUser): void
    {
        $isCreator = $event->created_by === $currentUser->id;
        $isAdmin = $currentUser->role === 'ADMIN';

        if (!$isCreator && !$isAdmin) {
            throw new \RuntimeException('You do not have permission to delete this event');
        }

        if ($isCreator && !$isAdmin && $event->status === 'APPROVED') {
            throw new \RuntimeException('Cannot delete an approved event. Contact an admin to cancel it.');
        }

        EventParticipant::where('event_id', $event->id)->delete();
        EventReport::where('event_id', $event->id)->delete();
        $event->delete();
    }
}
