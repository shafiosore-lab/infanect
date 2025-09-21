<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
        'category',
        'age_group',
        'difficulty_level',
        'max_participants',
        'price',
        'duration_minutes',
        'start_date',
        'end_date',
        'location',
        'status',
        'created_by',
        'provider_id',
        'requirements',
        'tags',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'requirements' => 'array',
        'tags' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'published');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeCategory($query, $category)
    {
        return $category ? $query->where('category', $category) : $query;
    }

    public function scopeByAgeGroup($query, $ageGroup)
    {
        return $query->where('age_group', $ageGroup);
    }

    public function scopeByProvider($query, $providerId)
    {
        return $query->where('provider_id', $providerId);
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

    public function scopeForBondingProviders($query)
    {
        return $query->whereHas('provider', function($q){
            $q->where('category', 'Bonding')->orWhere('type', 'provider-bonding');
        });
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return 'KSh ' . number_format($this->price, 0);
    }

    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return $hours . 'h ' . $minutes . 'm';
        } elseif ($hours > 0) {
            return $hours . 'h';
        } else {
            return $minutes . 'm';
        }
    }

    // Multi-Currency Conversion
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
