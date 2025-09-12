<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentChunk extends Model
{
    use HasFactory;

    protected $fillable = ['document_id','chunk_index','text','embedding','indexed'];

    protected $casts = ['embedding' => 'array', 'indexed' => 'boolean'];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
