<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\EventReport;
use App\Models\User;
use App\Services\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    public function __construct(private EventService $eventService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = Event::with(['university', 'createdBy'])->orderByDesc('created_at');

        if ($request->filled('universityId')) {
            $query->where('university_id', $request->input('universityId'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $events = $query->get();
        $response = $events->map(fn (Event $event) => $this->mapEvent($event))->values();

        return response()->json($response);
    }

    public function show(int $id): JsonResponse
    {
        $event = Event::with(['university', 'createdBy'])->findOrFail($id);
        return response()->json($this->mapEvent($event));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|min:3',
            'description' => 'required|string',
            'location' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'type' => 'required|string',
            'universityId' => 'nullable|integer',
            'maxOrganizers' => 'nullable|integer',
            'maxVolunteers' => 'nullable|integer',
            'maxAttendees' => 'nullable|integer',
            'organizerPoints' => 'nullable|integer',
            'volunteerPoints' => 'nullable|integer',
            'attendeePoints' => 'nullable|integer',
            'creatorParticipates' => 'nullable|boolean',
            'creatorRole' => 'nullable|string',
        ]);

        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $event = $this->eventService->createEvent($data, $user);
        $event->load(['university', 'createdBy']);

        return response()->json($this->mapEvent($event));
    }

    public function update(int $id, Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|min:3',
            'description' => 'required|string',
            'location' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'type' => 'required|string',
            'universityId' => 'nullable|integer',
            'maxOrganizers' => 'nullable|integer',
            'maxVolunteers' => 'nullable|integer',
            'maxAttendees' => 'nullable|integer',
            'organizerPoints' => 'nullable|integer',
            'volunteerPoints' => 'nullable|integer',
            'attendeePoints' => 'nullable|integer',
        ]);

        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $event = Event::findOrFail($id);

        try {
            $event = $this->eventService->updateEvent($event, $data, $user);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        $event->load(['university', 'createdBy']);
        return response()->json($this->mapEvent($event));
    }

    public function join(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'role' => 'required|string',
        ]);

        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $event = Event::findOrFail($id);
        try {
            $this->eventService->joinEvent($event, $user, $data['role']);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json(['message' => 'Joined event']);
    }

    public function leave(Request $request, int $id): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $event = Event::findOrFail($id);
        try {
            $this->eventService->leaveEvent($event, $user);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json(['message' => 'Left event']);
    }

    public function approve(Request $request, int $id): JsonResponse
    {
        $event = Event::findOrFail($id);
        try {
            $this->eventService->approveEvent($event);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
        return response()->json(['message' => 'Event approved']);
    }

    public function reject(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'reason' => 'nullable|string',
        ]);
        $event = Event::findOrFail($id);
        $this->eventService->rejectEvent($event, $data['reason'] ?? '');
        return response()->json(['message' => 'Event rejected']);
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'reason' => 'nullable|string',
        ]);
        $event = Event::findOrFail($id);
        $this->eventService->cancelEvent($event, $data['reason'] ?? '');
        return response()->json(['message' => 'Event cancelled']);
    }

    public function myEvents(Request $request): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $events = Event::with(['university', 'createdBy'])
            ->where('created_by', $user->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Event $event) => $this->mapEvent($event))
            ->values();

        return response()->json($events);
    }

    public function myParticipations(Request $request): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $participations = EventParticipant::with(['event', 'event.university'])
            ->where('user_id', $user->id)
            ->get()
            ->map(function (EventParticipant $participant) {
                return [
                    'participantId' => $participant->id,
                    'role' => $participant->role,
                    'pointsAwarded' => $participant->points_awarded,
                    'event' => $participant->event ? $this->mapEvent($participant->event) : null,
                ];
            })
            ->values();

        return response()->json($participations);
    }

    public function participants(int $id): JsonResponse
    {
        $participants = EventParticipant::with('user')
            ->where('event_id', $id)
            ->get()
            ->map(function (EventParticipant $participant) {
                return [
                    'participantId' => $participant->id,
                    'role' => $participant->role,
                    'pointsAwarded' => $participant->points_awarded,
                    'user' => [
                        'userId' => $participant->user?->id,
                        'name' => $participant->user?->name,
                        'email' => $participant->user?->email,
                    ],
                ];
            })
            ->values();

        return response()->json($participants);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $event = Event::findOrFail($id);
        try {
            $this->eventService->deleteEvent($event, $user);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json(['message' => 'Event deleted']);
    }

    private function mapEvent(Event $event): array
    {
        $reportCount = EventReport::where('event_id', $event->id)->count();

        return [
            'eventId' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'location' => $event->location,
            'startDate' => $event->start_date ? Carbon::parse($event->start_date)->toIso8601String() : null,
            'endDate' => $event->end_date ? Carbon::parse($event->end_date)->toIso8601String() : null,
            'type' => $event->type,
            'status' => $event->status,
            'maxOrganizers' => $event->max_organizers,
            'maxVolunteers' => $event->max_volunteers,
            'maxAttendees' => $event->max_attendees,
            'organizerPoints' => $event->organizer_points,
            'volunteerPoints' => $event->volunteer_points,
            'attendeePoints' => $event->attendee_points,
            'createdAt' => $event->created_at ? Carbon::parse($event->created_at)->toIso8601String() : null,
            'reportCount' => $reportCount,
            'universityId' => $event->university_id,
            'university' => $event->university ? [
                'universityId' => $event->university->id,
                'name' => $event->university->name,
                'emailDomain' => $event->university->email_domain,
            ] : null,
            'creator' => $event->createdBy ? [
                'userId' => $event->createdBy->id,
                'name' => $event->createdBy->name,
                'email' => $event->createdBy->email,
            ] : null,
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
