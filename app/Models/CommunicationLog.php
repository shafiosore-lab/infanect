<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','provider_id','channel','type','message','status','sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function provider() { return $this->belongsTo(User::class, 'provider_id'); }
}
