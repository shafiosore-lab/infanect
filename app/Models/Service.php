<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'user_id',           // optional, if linked to a user
        'name',
        'category',
        'description',
        'price',
        'currency',
        'duration_minutes',
        'availability',
        'attachments',
        'is_active',
        'is_approved',       // Approval status
        'image',             // Optional service image/banner
        'category_id',       // FK -> Category
        'tenant_id',         // FK -> Tenant for multi-tenant SaaS
        'metadata',          // Flexible JSON storage
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
        'metadata'  => 'array',
        'price'     => 'decimal:2',
        'availability' => 'array',
        'attachments' => 'array',
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
        return $this->belongsTo(Provider::class, 'provider_id');
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
    public function scopeOfferedByProfessional($query)
    {
        return $query->whereHas('provider', function($q){
            $q->where('status', 'approved')
              ->where('category', 'Professional')
              ->orWhere('provider_type','provider-professional');
        });
    }

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

    /**
     * Return available time slots for a given date using availability JSON.
     */
    public function availableSlots(string $date, ?string $outputTimezone = null): array
    {
        $duration = $this->duration_minutes ?? 60;
        $availability = $this->availability ?? [];

        $dayKey = strtolower(Carbon::parse($date)->format('D'));
        $dayName = strtolower(Carbon::parse($date)->format('l'));
        $ranges = $availability[$dayKey] ?? $availability[$dayName] ?? [];

        $slots = [];

        if (empty($ranges) || !is_array($ranges)) return $slots;

        foreach ($ranges as $range) {
            if (!str_contains($range, '-')) continue;

            [$startTime, $endTime] = explode('-', $range);
            $cursor = Carbon::createFromFormat('Y-m-d H:i', $date.' '.trim($startTime), config('app.timezone'));
            $endTimeObj = Carbon::createFromFormat('Y-m-d H:i', $date.' '.trim($endTime), config('app.timezone'));

            while ($cursor->lt($endTimeObj)) {
                $slotEnd = (clone $cursor)->addMinutes($duration);
                if ($slotEnd->gt($endTimeObj)) break;

                $cursorUtc = $cursor->copy()->setTimezone('UTC');
                $slotEndUtc = $slotEnd->copy()->setTimezone('UTC');

                $exists = $this->bookings()->where('status', '!=', 'canceled')
                    ->where(function($q) use ($cursorUtc, $slotEndUtc) {
                        $q->whereBetween('start_at', [$cursorUtc->toDateTimeString(), $slotEndUtc->toDateTimeString()])
                          ->orWhereBetween('end_at', [$cursorUtc->toDateTimeString(), $slotEndUtc->toDateTimeString()])
                          ->orWhere(function($q2) use ($cursorUtc, $slotEndUtc) {
                              $q2->where('start_at', '<=', $cursorUtc->toDateTimeString())
                                 ->where('end_at', '>=', $slotEndUtc->toDateTimeString());
                          });
                    })->exists();

                if (!$exists) {
                    $startOut = $cursor->copy();
                    $endOut = $slotEnd->copy();
                    if ($outputTimezone) {
                        $startOut = $startOut->setTimezone($outputTimezone);
                        $endOut = $endOut->setTimezone($outputTimezone);
                    }
                    $slots[] = ['start' => $startOut->toDateTimeString(), 'end' => $endOut->toDateTimeString()];
                }

                $cursor->addMinutes($duration);
            }
        }

        return $slots;
    }
}
