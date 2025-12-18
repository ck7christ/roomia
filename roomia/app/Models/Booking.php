<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';
    //
    protected $fillable = [
        'room_type_id',
        'guest_id',
        'guest_count',
        'check_in',
        'check_out',
        'total_price',
        'status',

        'cancelled_at',
        'cancelled_by_id',
        'cancelled_by_type',
        'cancel_reason',

        'voucher_id',
        'voucher_code',
        'voucher_discount_amount',
        'voucher_snapshot',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'guest_count' => 'integer',
        'total_price' => 'decimal:2',

        'cancelled_at' => 'datetime',

        'voucher_discount_amount' => 'decimal:2',
        'voucher_snapshot' => 'array',
    ];

    /**
     * Loại phòng được đặt
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function guest()
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }
    public function isCancellable(): bool
    {
        // Nếu đã hủy / hoàn thành rồi thì không hủy nữa
        if (
            in_array($this->status, [
                self::STATUS_CANCELLED,
                self::STATUS_COMPLETED,
            ])
        ) {
            return false;
        }

        // Không cho hủy nếu ngày check-in đã qua
        if (optional($this->check_in)->isPast()) {
            return false;
        }

        // Tùy rule của bạn, tạm cho phép hủy khi đang pending hoặc confirmed
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
        ]);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Voucher::class);
    }


}
