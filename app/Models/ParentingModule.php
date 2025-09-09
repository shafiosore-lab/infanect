<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentingModule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category',
        'difficulty_level',
        'estimated_duration',
        'language',
        'is_premium',
        'is_published',
        'created_by',
        'tags',
        'view_count',
        'completion_count',
        'rating',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_premium' => 'boolean',
        'is_published' => 'boolean',
        'rating' => 'decimal:2',
        'view_count' => 'integer',
        'completion_count' => 'integer',
        'estimated_duration' => 'integer',
    ];

    /**
     * Relationships
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contents()
    {
        return $this->hasMany(ModuleContent::class, 'module_id')->orderBy('order');
    }

    public function userProgress()
    {
        return $this->hasMany(UserModuleProgress::class, 'module_id');
    }

    public function aiRecommendations()
    {
        return $this->hasMany(AiRecommendation::class, 'module_id');
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    public function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    public function scopeForUser($query, $user)
    {
        // All users have access to all content
        return $query;
    }

    /**
     * Accessors & Mutators
     */
    public function getAverageRatingAttribute()
    {
        return $this->userProgress()
            ->whereNotNull('rating')
            ->avg('rating') ?? 0;
    }

    public function getCompletionRateAttribute()
    {
        if ($this->view_count == 0) return 0;
        return round(($this->completion_count / $this->view_count) * 100, 2);
    }

    public function getTotalDurationAttribute()
    {
        return $this->contents()->sum('duration') ?? $this->estimated_duration;
    }

    /**
     * Helper Methods
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function incrementCompletionCount()
    {
        $this->increment('completion_count');
    }

    public function isCompletedBy(User $user)
    {
        return $this->userProgress()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->exists();
    }

    public function getProgressFor(User $user)
    {
        return $this->userProgress()
            ->where('user_id', $user->id)
            ->first();
    }
}
