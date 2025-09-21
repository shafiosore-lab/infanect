<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'provider_type',
        'kyc_status',
        'specializations',
        'bio',
        'hourly_rate',
        'is_active',
    ];

    protected $casts = [
        'specializations' => 'array',
        'hourly_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'provider_id', 'user_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'provider_id', 'user_id');
    }

    public function clients()
    {
        return $this->belongsToMany(User::class, 'bookings', 'provider_id', 'user_id')
            ->distinct();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'provider_id', 'user_id');
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Booking::class, 'provider_id', 'booking_id', 'user_id', 'id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('provider_type', $type);
    }

    public function scopeVerified($query)
    {
        return $query->where('kyc_status', 'approved');
    }

    /**
     * Accessors & Helper Methods
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    public function getMonthlyRevenueAttribute()
    {
        return $this->bookings()
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
    }

    public function getCompletionRateAttribute()
    {
        $total = $this->bookings()->count();
        if ($total === 0) return 0;

        $completed = $this->bookings()->where('status', 'completed')->count();
        return round(($completed / $total) * 100, 1);
    }
}
