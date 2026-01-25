<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointsLog extends Model
{
    use HasFactory;

    protected $table = 'points_log';

    protected $fillable = [
        'user_id',
        'source_type',
        'source_id',
        'points',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
