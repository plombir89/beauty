<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingRequest extends Model
{
    public const StatusPending = 'pending';

    protected $fillable = [
        'specialist_id',
        'service_id',
        'locale',
        'name',
        'email',
        'phone',
        'message',
        'status',
        'source_url',
        'ip_address',
        'user_agent',
    ];

    protected $attributes = [
        'status' => self::StatusPending,
    ];

    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
