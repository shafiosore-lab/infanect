<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['filename','original_name','mime','size','meta'];

    protected $casts = ['meta' => 'array'];

    public function chunks()
    {
        return $this->hasMany(DocumentChunk::class);
    }
}
