<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderDocument extends Model
{
    protected $fillable = ['provider_id','type','path','meta'];

    protected $casts = ['meta' => 'array'];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
