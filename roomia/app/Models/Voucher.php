<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\VoucherRedemption;

class Voucher extends Model
{
    //
    public const TYPE_PERCENT = 'percent';
    public const TYPE_FIXED = 'fixed';

    public const TYPES = [
        self::TYPE_PERCENT,
        self::TYPE_FIXED,
    ];

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_subtotal',
        'max_discount',
        'usage_limit',
        'per_user_limit',
        'used_count',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'value' => 'decimal:2',
        'min_subtotal' => 'decimal:2',
        'max_discount' => 'decimal:2',
    ];

    public function redemptions(): HasMany
    {
        return $this->hasMany(VoucherRedemption::class);
    }

    public function setCodeAttribute($value): void
    {
        $this->attributes['code'] = strtoupper(trim((string) $value));
    }

    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active)
            return false;

        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at))
            return false;
        if ($this->ends_at && $now->gt($this->ends_at))
            return false;

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit)
            return false;

        return true;
    }

    public function canApply(?User $user, float $subtotal): bool
    {
        if (!$this->isCurrentlyActive())
            return false;

        if ($this->min_subtotal !== null && $subtotal < (float) $this->min_subtotal) {
            return false;
        }

        if ($user && $this->per_user_limit !== null) {
            $usedByUser = $this->redemptions()->where('user_id', $user->id)->count();
            if ($usedByUser >= $this->per_user_limit)
                return false;
        }

        return true;
    }

    public function discountFor(float $subtotal): float
    {
        if ($subtotal <= 0)
            return 0;

        $discount = 0.0;

        if ($this->type === self::TYPE_PERCENT) {
            $discount = $subtotal * ((float) $this->value / 100.0);
            if ($this->max_discount !== null) {
                $discount = min($discount, (float) $this->max_discount);
            }
        } else {
            $discount = (float) $this->value;
        }

        $discount = min($discount, $subtotal);
        return max(0, (float) round($discount, 2));
    }

}
