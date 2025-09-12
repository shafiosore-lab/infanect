<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityPreference extends Model
{
    protected $fillable = ['user_id','preferences'];

    protected $casts = ['preferences' => 'array'];
}
