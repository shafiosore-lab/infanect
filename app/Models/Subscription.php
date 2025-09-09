<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'plan',
        'amount',
        'currency',
        'status',
        'payment_method',
        'payment_reference',
        'is_trial',
        'trial_days',
        'trial_ends_at',
        'auto_renew',
        'country',
        'platform',
        'starts_at',
        'expires_at',
        'cancelled_at',
        'features',
        'billing_cycle',
        'provider',
        'notified_expiry',
    ];

    protected $casts = [
        'features'       => 'array',
        'is_trial'       => 'boolean',
        'auto_renew'     => 'boolean',
        'notified_expiry'=> 'boolean',
        'starts_at'      => 'datetime',
        'expires_at'     => 'datetime',
        'trial_ends_at'  => 'datetime',
        'cancelled_at'   => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where(function ($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                     ->orWhere('expires_at', '<', now());
    }

    public function scopeTrial($query)
    {
        return $query->where('is_trial', true);
    }

    /**
     * Helpers
     */
    public function isTrial(): bool
    {
        return $this->is_trial && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isExpiringSoon(int $days = 7): bool
    {
        return $this->expires_at &&
               $this->expires_at->between(now(), now()->addDays($days));
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    public function isRenewing(): bool
    {
        return $this->auto_renew && $this->isActive();
    }

    public function formattedAmount(): string
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }
}
