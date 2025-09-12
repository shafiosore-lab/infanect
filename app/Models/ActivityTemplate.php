<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','age_groups','indoor_outdoor','energy_level','duration','cost_tier','accessibility_tags','tags','locale','created_by','provider_id'];

    protected $casts = [
        'age_groups' => 'array',
        'accessibility_tags' => 'array',
        'tags' => 'array',
    ];

    public function provider() { return $this->belongsTo(User::class, 'provider_id'); }
}
