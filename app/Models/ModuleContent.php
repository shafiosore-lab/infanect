<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ModuleContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'description',
        'content_type',
        'file_path',
        'file_url',
        'original_filename',
        'mime_type',
        'file_size',
        'duration',
        'order',
        'is_preview',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_preview' => 'boolean',
        'file_size' => 'integer',
        'duration' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Relationships
     */
    public function module()
    {
        return $this->belongsTo(ParentingModule::class, 'module_id');
    }

    public function userProgress()
    {
        return $this->hasMany(UserModuleProgress::class, 'content_id');
    }

    /**
     * Scopes
     */
    public function scopePreview($query)
    {
        return $query->where('is_preview', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('content_type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Accessors
     */
    public function getFileUrlAttribute($value)
    {
        if ($this->file_path) {
            return Storage::url($this->file_path);
        }
        return $value;
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) return null;

        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) return null;

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->file_size;
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Helper Methods
     */
    public function isVideo()
    {
        return $this->content_type === 'video';
    }

    public function isAudio()
    {
        return $this->content_type === 'audio';
    }

    public function isPdf()
    {
        return $this->content_type === 'pdf';
    }

    public function isText()
    {
        return $this->content_type === 'text';
    }

    public function canAccessBy(User $user = null)
    {
        // All users can access all content
        return true;
    }

    public function getTranscription()
    {
        return $this->metadata['transcription'] ?? null;
    }

    public function getSubtitles()
    {
        return $this->metadata['subtitles'] ?? null;
    }
}
