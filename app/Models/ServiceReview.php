<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'professional_service_id',
        'client_id',
        'rating',
        'review',
        'provider_response', // Optional response from the service provider
    ];

    /**
     * Relationships
     */

    /**
     * The service that this review belongs to.
     */
    public function service()
    {
        return $this->belongsTo(ProfessionalService::class, 'professional_service_id');
    }

    /**
     * The client who submitted the review.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Accessors
     */

    /**
     * Get the rating as a float for consistency.
     */
    public function getRatingAttribute($value)
    {
        return (float) $value;
    }
}
