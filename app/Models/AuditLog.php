<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['user_id','event','meta','ip'];

    protected $casts = ['meta' => 'array'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
