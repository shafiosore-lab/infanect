<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'business_name',
        'category',
        'country',
        'city',
        'timezone',
        'language',
        'status',
        'business_license_path',
        'id_document_path',
        'state',
        'email',
        'phone',
        'address',
        'postal_code',
        'latitude',
        'longitude',
        'logo',
        'is_available',
        'avg_rating',
        'total_reviews',
        'total_revenue',
        'location',
        'rating',
        'services',
        'bio',
        'price',
        'image_url'
    ];

    protected $casts = [
        'latitude'     => 'float',
        'longitude'    => 'float',
        'is_available' => 'boolean',
        'avg_rating'   => 'float',
        'total_reviews' => 'integer',
        'total_revenue'  => 'float',
        'services' => 'array',
        'rating' => 'decimal:1'
    ];

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(\App\Models\Service::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(\App\Models\Activity::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(\App\Models\ProviderDocument::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(\App\Models\Transaction::class);
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

    public function isProfessional()
    {
        return in_array($this->category, ['Professional','professional','Provider Professional']) || $this->type === 'provider-professional';
    }

    public function isBonding()
    {
        return in_array($this->category, ['Bonding','bonding']) || $this->type === 'provider-bonding';
    }

    // Scope for filtering by location
    public function scopeLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    // Scope for filtering by rating
    public function scopeRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    // Scope for filtering by service
    public function scopeService($query, $service)
    {
        return $query->whereJsonContains('services', $service);
    }
}
