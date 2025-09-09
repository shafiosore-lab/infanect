<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModuleProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'module_id',
        'content_id',
        'status',                // not_started, in_progress, completed, paused, dropped
        'progress_percentage',   // integer: 0–100
        'time_spent',            // in minutes
        'started_at',
        'completed_at',
        'last_accessed_at',
        'progress_data',         // JSON: detailed interactions
        'is_favorited',
        'rating',
        'notes',                 // localized notes or reflections
        'language',              // for international content (e.g., en, fr, sw, ar)
        'device_type',           // e.g., mobile, tablet, web
        'region',                // region/country code (ISO 3166)
        'version',               // module versioning for updates
    ];

    protected $casts = [
        'started_at'        => 'datetime',
        'completed_at'      => 'datetime',
        'last_accessed_at'  => 'datetime',
        'progress_data'     => 'array',
        'is_favorited'      => 'boolean',
        'progress_percentage' => 'integer',
        'time_spent'        => 'integer',
        'rating'            => 'integer',
    ];

    /* -----------------------------
     | Relationships
     ------------------------------*/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(ParentingModule::class, 'module_id');
    }

    public function trainingModule()
    {
        return $this->belongsTo(\App\Models\TrainingModule::class, 'module_id');
    }

    public function getModuleAttribute()
    {
        // Try parenting module first, then training module
        if ($this->relationLoaded('module') && $this->module) {
            return $this->module;
        }

        if ($this->relationLoaded('trainingModule') && $this->trainingModule) {
            return $this->trainingModule;
        }

        // Try to load the appropriate module
        $parentingModule = ParentingModule::find($this->module_id);
        if ($parentingModule) {
            return $parentingModule;
        }

        $trainingModule = \App\Models\TrainingModule::find($this->module_id);
        if ($trainingModule) {
            return $trainingModule;
        }

        return null;
    }

    public function currentContent()
    {
        return $this->belongsTo(ModuleContent::class, 'content_id');
    }

    /* -----------------------------
     | Scopes
     ------------------------------*/
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeNotStarted($query)
    {
        return $query->where('status', 'not_started');
    }

    public function scopeFavorited($query)
    {
        return $query->where('is_favorited', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    /* -----------------------------
     | Accessors
     ------------------------------*/
    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getIsInProgressAttribute()
    {
        return $this->status === 'in_progress';
    }

    public function getFormattedTimeSpentAttribute()
    {
        $hours = floor($this->time_spent / 60);
        $minutes = $this->time_spent % 60;

        return $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
    }

    /* -----------------------------
     | Helper Methods
     ------------------------------*/
    public function markAsStarted()
    {
        if (!$this->started_at) {
            $this->started_at = now();
            $this->status = 'in_progress';
            $this->save();
        }
    }

    public function markAsCompleted()
    {
        $this->completed_at = now();
        $this->status = 'completed';
        $this->progress_percentage = 100;
        $this->save();

        if ($this->module) {
            $this->module->incrementCompletionCount();
        }
    }

    public function updateProgress($percentage, $timeSpent = null)
    {
        $this->progress_percentage = min(100, max(0, $percentage));
        $this->last_accessed_at = now();

        if ($timeSpent) {
            $this->time_spent += $timeSpent;
        }

        if ($this->progress_percentage > 0 && $this->status === 'not_started') {
            $this->markAsStarted();
        }

        if ($this->progress_percentage === 100) {
            $this->markAsCompleted();
        }

        $this->save();
    }

    public function addTimeSpent($minutes)
    {
        $this->time_spent += $minutes;
        $this->last_accessed_at = now();
        $this->save();
    }

    public function toggleFavorite()
    {
        $this->is_favorited = !$this->is_favorited;
        $this->save();
    }

    public function rate($rating)
    {
        $this->rating = max(1, min(5, $rating));
        $this->save();
    }

    /* -----------------------------
     | Analytics / Global Expansion
     ------------------------------*/
    public function calculateEngagementScore()
    {
        // Example: weighted score based on progress, time, and rating
        $score = ($this->progress_percentage * 0.5) +
                 (min($this->time_spent, 300) / 300 * 30) +
                 ($this->rating * 4);

        return round(min(100, $score));
    }

    public static function averageProgressForUser($userId)
    {
        return static::where('user_id', $userId)->avg('progress_percentage') ?? 0;
    }

    public static function completionRateByRegion($region)
    {
        $total = static::forRegion($region)->count();
        $completed = static::forRegion($region)->completed()->count();

        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }
}
