<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceInsight extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_id',       // Link to the service
        'views',            // Number of times the service has been viewed
        'bookings',         // Number of bookings for this service
        'ratings',          // Average rating (optional)
        'feedback_count',   // Number of feedback/reviews
        'country',          // Country for multi-region analysis
        'date',             // Date of the insight
    ];

    // Relationship: each insight belongs to a service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // Scope: filter insights by country
    public function scopeCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    // Scope: filter insights by date
    public function scopeDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }
}
