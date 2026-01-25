<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'university_id',
        'name',
        'email',
        'password',
        'email_verified',
        'role',
        'points',
        'current_badge_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified' => 'boolean',
    ];

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function currentBadge(): BelongsTo
    {
        return $this->belongsTo(Badge::class, 'current_badge_id');
    }

    public function eventsCreated(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    public function eventParticipants(): HasMany
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges');
    }

    public function pointsLogs(): HasMany
    {
        return $this->hasMany(PointsLog::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function eventRequests(): HasMany
    {
        return $this->hasMany(EventRequest::class);
    }

    public function eventParticipationRequests(): HasMany
    {
        return $this->hasMany(EventParticipationRequest::class);
    }

    public function respondedEventParticipationRequests(): HasMany
    {
        return $this->hasMany(EventParticipationRequest::class, 'responded_by');
    }

    public function blogReports(): HasMany
    {
        return $this->hasMany(BlogReport::class, 'reported_by');
    }

    public function eventReports(): HasMany
    {
        return $this->hasMany(EventReport::class, 'reported_by');
    }

}
