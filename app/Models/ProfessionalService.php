<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalService extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_profile_id',
        'name',
        'category',
        'description',
        'price',
        'currency',
        'duration_minutes',
        'availability',
        'attachments',
        'is_active',
    ];

    protected $casts = [
        'availability' => 'array',
        'attachments' => 'array',
        'price' => 'decimal:2',
    ];

    public function provider()
    {
        return $this->belongsTo(ProviderProfile::class, 'provider_profile_id');
    }

    public function bookings()
    {
        return $this->hasMany(ServiceBooking::class);
    }

    public function reviews()
    {
        return $this->hasMany(ServiceReview::class);
    }
}
