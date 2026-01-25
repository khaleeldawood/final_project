<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'title',
        'description',
        'location',
        'start_date',
        'end_date',
        'type',
        'status',
        'max_organizers',
        'max_volunteers',
        'max_attendees',
        'organizer_points',
        'volunteer_points',
        'attendee_points',
        'created_by',
    ];

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(EventRequest::class);
    }

    public function participationRequests(): HasMany
    {
        return $this->hasMany(EventParticipationRequest::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(EventReport::class);
    }
}
