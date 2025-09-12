<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'professional_service_id',
        'client_id',
        'rating',
        'review',
        'provider_response',
    ];

    public function service()
    {
        return $this->belongsTo(ProfessionalService::class, 'professional_service_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
