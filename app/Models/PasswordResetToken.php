<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $table = 'password_reset_tokens';

    public $timestamps = false;

    protected $fillable = [
        'email',
        'user_id',
        'token_hash',
        'expiry_date',
        'used',
        'created_at',
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
        'used' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        if ($this->used) {
            return false;
        }

        if (!$this->expiry_date instanceof Carbon) {
            return false;
        }

        return $this->expiry_date->isFuture();
    }
}
