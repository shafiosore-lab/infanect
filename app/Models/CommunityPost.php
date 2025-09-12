<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    protected $fillable = ['user_id','content','group','status','meta'];

    protected $casts = ['meta' => 'array'];
}
