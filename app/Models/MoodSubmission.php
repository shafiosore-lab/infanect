<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodSubmission extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','mood','mood_score','availability','location','timezone','language'];

    protected $casts = [
        'availability' => 'array',
        'location' => 'array',
    ];

    public function user() { return $this->belongsTo(User::class); }
}
