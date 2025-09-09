<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'module_id',
        'recommendation_type',
        'confidence_score',
        'reasoning',
        'user_profile_data',
        'recommendation_metadata',
        'is_viewed',
        'is_clicked',
        'expires_at',
    ];

    protected $casts = [
        'confidence_score' => 'decimal:2',
        'user_profile_data' => 'array',
        'recommendation_metadata' => 'array',
        'is_viewed' => 'boolean',
        'is_clicked' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(ParentingModule::class, 'module_id');
    }

    /**
     * Scopes
     */
    public function scopeViewed($query)
    {
        return $query->where('is_viewed', true);
    }

    public function scopeClicked($query)
    {
        return $query->where('is_clicked', true);
    }

    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeByType($query, $type)
    {
        return $query->where('recommendation_type', $type);
    }

    public function scopeHighConfidence($query, $threshold = 0.7)
    {
        return $query->where('confidence_score', '>=', $threshold);
    }

    /**
     * Accessors
     */
    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getClickThroughRateAttribute()
    {
        if (!$this->is_viewed) return 0;
        return $this->is_clicked ? 100 : 0;
    }

    /**
     * Helper Methods
     */
    public function markAsViewed()
    {
        if (!$this->is_viewed) {
            $this->is_viewed = true;
            $this->save();
        }
    }

    public function markAsClicked()
    {
        $this->is_clicked = true;
        $this->markAsViewed();
        $this->save();
    }

    public function extendExpiry($days = 7)
    {
        $this->expires_at = now()->addDays($days);
        $this->save();
    }

    /**
     * Static Methods for AI Recommendation Generation
     */
    public static function generateForUser(User $user, $type = 'personalized', $limit = 5)
    {
        $existingRecommendations = self::where('user_id', $user->id)
            ->where('recommendation_type', $type)
            ->active()
            ->pluck('module_id')
            ->toArray();

        $modules = ParentingModule::published()
            ->whereNotIn('id', $existingRecommendations)
            ->forUser($user)
            ->inRandomOrder()
            ->limit($limit)
            ->get();

        $recommendations = [];
        foreach ($modules as $module) {
            $confidence = self::calculateConfidenceScore($user, $module, $type);

            $recommendations[] = self::create([
                'user_id' => $user->id,
                'module_id' => $module->id,
                'recommendation_type' => $type,
                'confidence_score' => $confidence,
                'reasoning' => self::generateReasoning($user, $module, $type),
                'user_profile_data' => self::getUserProfileSnapshot($user),
                'expires_at' => now()->addDays(7),
            ]);
        }

        return $recommendations;
    }

    private static function calculateConfidenceScore(User $user, ParentingModule $module, $type)
    {
        $score = 0.5; // Base score

        switch ($type) {
            case 'personalized':
                // Based on user's learning history and preferences
                $userProgress = $user->moduleProgress()->completed()->get();
                if ($userProgress->isNotEmpty()) {
                    // Similar category modules get higher score
                    $completedCategories = $userProgress->pluck('module.category')->unique();
                    if ($completedCategories->contains($module->category)) {
                        $score += 0.2;
                    }

                    // Similar difficulty level
                    $completedLevels = $userProgress->pluck('module.difficulty_level')->unique();
                    if ($completedLevels->contains($module->difficulty_level)) {
                        $score += 0.1;
                    }
                }
                break;

            case 'trending':
                // Based on popularity and recent activity
                $completionRate = $module->completion_rate ?? 0;
                $score = min(0.9, 0.5 + ($completionRate / 200)); // Max 90% confidence
                break;

            case 'completion_based':
                // Recommend next modules in sequence
                $score += 0.3; // Higher confidence for sequential recommendations
                break;
        }

        return min(1.0, max(0.0, $score));
    }

    private static function generateReasoning(User $user, ParentingModule $module, $type)
    {
        switch ($type) {
            case 'personalized':
                return "Based on your interest in {$module->category} topics and {$module->difficulty_level} level content.";

            case 'trending':
                return "This module is popular among parents with {$module->completion_rate}% completion rate.";

            case 'completion_based':
                return "Great next step after completing similar parenting modules.";

            default:
                return "Recommended based on your learning preferences.";
        }
    }

    private static function getUserProfileSnapshot(User $user)
    {
        $progress = $user->moduleProgress()->with('module')->get();

        return [
            'total_modules_completed' => $progress->where('status', 'completed')->count(),
            'favorite_categories' => $progress->where('status', 'completed')
                ->pluck('module.category')
                ->countBy()
                ->sortDesc()
                ->keys()
                ->take(3)
                ->toArray(),
            'preferred_difficulty' => $progress->where('status', 'completed')
                ->pluck('module.difficulty_level')
                ->countBy()
                ->sortDesc()
                ->keys()
                ->first(),
            'average_rating_given' => $progress->whereNotNull('rating')
                ->avg('rating') ?? 0,
            'last_activity' => $progress->max('last_accessed_at'),
        ];
    }
}
