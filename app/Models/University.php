<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class University extends Model
{
    use HasFactory;

    protected $appends = [
        'universityId',
        'emailDomain',
    ];

    protected $fillable = [
        'name',
        'description',
        'logo_url',
        'email_domain',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    public function getUniversityIdAttribute(): int
    {
        return $this->id;
    }

    public function getEmailDomainAttribute(): string
    {
        return $this->email_domain ?? '';
    }
}
