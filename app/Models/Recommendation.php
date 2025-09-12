<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','mood_submission_id','payload','score','generated_at'];

    protected $casts = ['payload' => 'array', 'generated_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function moodSubmission() { return $this->belongsTo(MoodSubmission::class); }
}
