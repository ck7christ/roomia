<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
class ContactMessage extends Model
{
    //
    public const STATUS_NEW = 'new';
    public const STATUS_SEEN = 'seen';
    public const STATUS_REPLIED = 'replied';
    public const STATUS_CLOSED = 'closed';

    public const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_SEEN,
        self::STATUS_REPLIED,
        self::STATUS_CLOSED,
    ];

    protected $fillable = [
        'user_id',
        'handled_by',
        'name',
        'email',
        'subject',
        'message',
        'status',
        'ip_address',
        'user_agent',
        'admin_note',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function scopeStatus($query, ?string $status)
    {
        if (!$status)
            return $query;
        return $query->where('status', $status);
    }

    public function scopeSearch($query, ?string $q)
    {
        if (!$q)
            return $query;
        return $query->where(function ($w) use ($q) {
            $w->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('subject', 'like', "%{$q}%")
                ->orWhere('message', 'like', "%{$q}%");
        });
    }
}
