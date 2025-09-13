<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'description',
        'services_offered',
        'location',
        'phone',
        'email',
        'website',
        'social_media_links',
        'certifications',
        'experience_years',
        'availability_schedule',
        'pricing_info',
        'photos',
        'videos',
        'is_verified',
        'verification_documents',
        'status',
        'rating',
        'total_reviews'
    ];

    protected $casts = [
        'services_offered' => 'array',
        'social_media_links' => 'array',
        'certifications' => 'array',
        'availability_schedule' => 'array',
        'pricing_info' => 'array',
        'photos' => 'array',
        'videos' => 'array',
        'verification_documents' => 'array',
        'is_verified' => 'boolean',
        'rating' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Scope for active providers (instead of using soft deletes)
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for verified providers
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
