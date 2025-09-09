<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',        // 🌍 Currency for global pricing
        'duration',        // Duration of service (in minutes/hours)
        'code',            // Optional unique code for international use
        'country',         // Country availability
        'language',        // Default language of service
        'is_active',       // Enable/disable service
        'is_approved',     // Approval status
        'image',           // Optional service image/banner
        'category_id',     // FK -> Category
        'provider_id',     // FK -> ServiceProvider
        'tenant_id',       // FK -> Tenant for multi-tenant SaaS
        'metadata',        // Flexible JSON storage
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata'  => 'array',
        'price'     => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'provider_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeByLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'like', '%' . $search . '%')
                         ->orWhere('description', 'like', '%' . $search . '%');
        }
        return $query;
    }

    public function scopeCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    public function scopePopular($query)
    {
        // Assuming popularity based on number of bookings
        return $query->withCount('bookings')->orderBy('bookings_count', 'desc');
    }

    public function scopeSortBy($query, $sort, $direction = 'asc')
    {
        $allowedSorts = ['name', 'price', 'created_at'];
        $direction = in_array(strtolower($direction), ['asc', 'desc']) ? $direction : 'asc';

        if (in_array($sort, $allowedSorts)) {
            return $query->orderBy($sort, $direction);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Accessors
     */
    public function getFormattedPriceAttribute()
    {
        return $this->currency . ' ' . number_format($this->price, 2);
    }

    public function getStatusLabelAttribute()
    {
        return $this->is_active ? __('Active') : __('Inactive');
    }
}
