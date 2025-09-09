<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model {
    use SoftDeletes;
    protected $fillable = [
        'title','description','file_path','ai_text','ai_audio_path','tags','level','tenant_id'
    ];
    protected $casts = [
        'tags' => 'array'
    ];
}
