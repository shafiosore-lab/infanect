<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceProvider extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
        'specialization',
        'availability',   // JSON schedule/slots
        'rating',         // Avg. rating
        'tenant_id',      // For multi-tenant SaaS
        'user_id',        // Optional link to User (account owner)
        'metadata',       // Flexible JSON storage
        'country',        // International support
        'language',       // Default language for communication
        'currency',       // Currency for service pricing
        'is_approved',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'availability' => 'array',
        'metadata'     => 'array',
        'rating'       => 'float',
    ];

    /**
     * Relationships
     */

    // Services offered by this provider
    public function services()
    {
        return $this->hasMany(Service::class, 'provider_id');
    }

    // Activities organized by this provider
    public function activities()
    {
        return $this->hasMany(Activity::class, 'provider_id');
    }

    // Optional linked user account
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Bookings made for the provider's services
    public function bookings()
    {
        return $this->hasManyThrough(
            Booking::class,   // Final model
            Service::class,   // Intermediate model
            'provider_id',    // FK on services table
            'service_id',     // FK on bookings table
            'id',             // Local key on provider
            'id'              // Local key on service
        );
    }

    /**
     * Scopes
     */

    // Scope to filter providers by tenant
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // Scope for active providers (not soft deleted)
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    // Scope for filtering by country
    public function scopeByCountry($query, string $country)
    {
        return $query->where('country', $country);
    }

    // Scope for filtering by specialization keyword
    public function scopeSpecializedIn($query, string $field)
    {
        return $query->where('specialization', 'LIKE', "%{$field}%");
    }

    /**
     * Accessors / Mutators
     */

    // Always return provider name in Title Case
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    // Format rating as 1 decimal place
    public function getRatingAttribute($value)
    {
        return number_format((float) $value, 1);
    }
}
