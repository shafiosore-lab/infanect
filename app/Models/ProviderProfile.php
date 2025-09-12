<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProviderProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'provider_type',
        'title',
        'bio',
        'phone',
        'currency',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->hasMany(ProfessionalService::class);
    }

    public function activities()
    {
        return $this->hasMany(\App\Models\Activity::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(\App\Models\Booking::class, \App\Models\Service::class, 'provider_id', 'service_id');
    }
}
