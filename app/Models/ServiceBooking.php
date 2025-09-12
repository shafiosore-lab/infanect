<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'professional_service_id',
        'client_id',
        'start_at',
        'end_at',
        'status',
        'amount',
        'currency',
        'payment_method',
        'payment_meta',
        'booking_meta',
    ];

    protected $casts = [
        'payment_meta' => 'array',
        'booking_meta' => 'array',
        'amount' => 'decimal:2',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public static $statuses = ['pending','confirmed','completed','canceled','refunded'];

    public function service()
    {
        return $this->belongsTo(ProfessionalService::class, 'professional_service_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function isOverlapping($start, $end)
    {
        return $this->where('professional_service_id', $this->professional_service_id)
            ->where('status', '!=', 'canceled')
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('start_at', [$start, $end])
                  ->orWhereBetween('end_at', [$start, $end])
                  ->orWhere(function($q2) use ($start, $end) {
                      $q2->where('start_at', '<=', $start)->where('end_at', '>=', $end);
                  });
            })->exists();
    }
}
