<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id','reviewer_name','country_code',
        'rating','comment','is_approved','translations'
    ];

    protected $casts = [
        'is_approved'   => 'boolean',
        'translations'  => 'array',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
