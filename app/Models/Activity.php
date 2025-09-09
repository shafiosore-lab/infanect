<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'category',
        'country',
        'region',
        'venue',
        'datetime',
        'price',
        'slots',
        'booking_link',
        'tenant_id',
        'provider_id',
        'meta',
        'is_approved',
        'description',
        'duration',
        'difficulty_level',
        'target_audience',
    ];

    protected $casts = [
        'datetime' => 'datetime',
        'meta' => 'array',
        'is_approved' => 'boolean',
        'price' => 'decimal:2',
        'slots' => 'integer',
    ];

    // Relationships
    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'provider_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // ======================
    // Availability
    // ======================

    // Get available slots
    public function availableSlots($includePending = true)
    {
        $query = $this->bookings();
        $query = $includePending
            ? $query->whereIn('status', ['pending', 'confirmed'])
            : $query->where('status', 'confirmed');

        $booked = $query->sum('participants');
        return max(0, $this->slots - $booked);
    }

    // ======================
    // Query Scopes
    // ======================

    public function scopeUpcoming($query)
    {
        return $query->where('datetime', '>', now())->orderBy('datetime', 'asc');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeSearch($query, $term)
    {
        if (!$term) return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('category', 'LIKE', "%{$term}%")
              ->orWhere('venue', 'LIKE', "%{$term}%")
              ->orWhereHas('provider', function ($providerQuery) use ($term) {
                  $providerQuery->where('name', 'LIKE', "%{$term}%");
              });
        });
    }

    public function scopeCategory($query, $category)
    {
        return $category ? $query->where('category', $category) : $query;
    }

    public function scopeLocation($query, $location)
    {
        if (!$location) return $query;

        return $query->where(function ($q) use ($location) {
            $q->where('venue', 'LIKE', "%{$location}%")
              ->orWhere('region', 'LIKE', "%{$location}%")
              ->orWhere('country', 'LIKE', "%{$location}%");
        });
    }

    public function scopeSortBy($query, $sortBy = 'datetime', $direction = 'asc')
    {
        $allowed = ['price', 'title', 'datetime', 'country', 'region'];
        $sortBy = in_array($sortBy, $allowed) ? $sortBy : 'datetime';
        $direction = in_array(strtolower($direction), ['asc', 'desc']) ? $direction : 'asc';
        return $query->orderBy($sortBy, $direction);
    }

    // ======================
    // Multi-Currency Conversion
    // ======================
    public function priceIn($currency = 'KES')
    {
        $rates = [
            'KES' => 1,         // Base currency
            'USD' => 0.0072,
            'EUR' => 0.0065,
            'GBP' => 0.0056,
        ];

        $rate = $rates[$currency] ?? 1;
        return round($this->price * $rate, 2);
    }
}
