<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioModule extends Model
{
    use HasFactory;

    protected $fillable = ['title','language','duration_secs','url','tts_text','tags'];

    protected $casts = ['tags' => 'array'];
}
