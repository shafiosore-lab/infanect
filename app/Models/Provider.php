<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'service_type',
        'email',
        'phone',
        'country_code',
        'currency_code',   // keep from first version
        'city',
        'state',
        'address',
        'postal_code',
        'latitude',
        'longitude',
        'logo',
        'is_available',
        'is_featured',
        'avg_rating',
        'total_reviews',
        'total_revenue',
    ];

    protected $casts = [
        'is_available'   => 'boolean',
        'avg_rating'     => 'decimal:2',
        'total_revenue'  => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Helpers
     */
    public function toggleAvailability()
    {
        $this->update(['is_available' => !$this->is_available]);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function totalRevenue()
    {
        // more accurate: only count completed transactions
        return $this->transactions()->where('status', 'completed')->sum('amount');
    }
}
