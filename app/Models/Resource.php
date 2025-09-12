<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['user_id','title','content','category','meta'];

    protected $casts = ['meta' => 'array'];
}
