<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // 🔗 Relationships
        'user_id',          // Client who booked
        'activity_id',      // Specific activity under a service
        'service_id',       // Main service category
        'provider_id',      // Service provider
        'tenant_id',        // For multi-tenant SaaS

        // 👤 Customer Info
        'customer_name',
        'customer_email',
        'customer_phone',
        'country',
        'country_code',

        // 📅 Booking details
        'booking_date',     // Legacy support
        'scheduled_at',     // Preferred for scheduling
        'status',           // pending, confirmed, cancelled, refunded, completed
        'location',
        'timezone',

        // 💰 Payment & financials
        'price',            // Base price
        'currency_code',    // ISO 4217 (USD, EUR, KES, etc.)
        'amount',           // Total booking cost
        'amount_paid',      // Actual paid amount
        'participants',     // Number of participants
        'discount',         // Discount applied
        'payment_method',   // e.g., card, mpesa, paypal
        'payment_ref',      // Transaction reference

        // ⭐ Engagement
        'rating',           // Optional rating from client
        'is_returning',     // Returning customer?

        // 📎 Extra info
        'reference_code',   // Unique booking reference
        'notes',
        'platform',         // Platform used to book (web, mobile app)
        'metadata',         // Flexible JSON storage
    ];

    protected $casts = [
        'booking_date'  => 'datetime',
        'scheduled_at'  => 'datetime',
        'metadata'      => 'array',
        'is_returning'  => 'boolean',
        'amount'        => 'decimal:2',
    ];

    protected $dates = [
        'scheduled_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * 🔗 Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * 📊 Scopes for reporting & analytics
     */
    public function scopeTotalEarnings($query)
    {
        return $query->sum('amount_paid');
    }

    public function scopeMonthlyEarnings($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->sum('amount_paid');
    }

    public function scopePendingPayouts($query)
    {
        return $query->where('status', 'pending')
                     ->sum('amount');
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByPlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }

    public function scopeByProvider($query, $providerId)
    {
        return $query->where('provider_id', $providerId);
    }

    public function scopeInternationalBookings($query, $baseCountry = 'Kenya')
    {
        return $query->where('country', '!=', $baseCountry);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', now())
            ->whereIn('status', ['confirmed', 'pending']);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('booking_date', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year);
    }

    /**
     * ⚡ Helpers
     */
    public function isPaid(): bool
    {
        return $this->amount_paid >= $this->amount;
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function formattedAmount(): string
    {
        return number_format($this->amount, 2) . ' ' . $this->currency_code;
    }

    public function formattedAmountPaid(): string
    {
        return number_format($this->amount_paid, 2) . ' ' . $this->currency_code;
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return 'KSh ' . number_format($this->amount, 0);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}
