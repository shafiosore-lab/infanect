<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingModule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category',
        'difficulty_level',
        'estimated_duration',
        'language',
        'document_content',
        'document_path',
        'document_type',
        'enable_ai_chat',
        'ai_chat_config',
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
        'document_content' => 'array',
        'ai_chat_config' => 'array',
        'is_premium' => 'boolean',
        'is_published' => 'boolean',
        'enable_ai_chat' => 'boolean',
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

    public function userProgress()
    {
        return $this->hasMany(UserModuleProgress::class, 'module_id');
    }

    public function aiChatConversations()
    {
        return $this->hasMany(AiChatConversation::class, 'module_id');
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

    public function scopeWithAiChat($query)
    {
        return $query->where('enable_ai_chat', true);
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

    public function getDocumentUrlAttribute()
    {
        return $this->document_path ? asset('storage/' . $this->document_path) : null;
    }

    public function getExtractedContentAttribute()
    {
        return $this->document_content ?? [];
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

    public function hasDocument()
    {
        return !empty($this->document_path) && file_exists(storage_path('app/public/' . $this->document_path));
    }

    public function canUseAiChat()
    {
        return $this->enable_ai_chat && (!empty($this->document_content) || $this->hasDocument());
    }

    /**
     * AI Chat Methods
     */
    public function extractDocumentContent()
    {
        if (!$this->hasDocument()) {
            return false;
        }

        // Use AI service to extract content from document
        $aiService = app(AIService::class);
        $content = $aiService->extractDocumentContent(storage_path('app/public/' . $this->document_path));

        if ($content) {
            $this->document_content = $content;
            $this->save();
            return true;
        }

        return false;
    }

    public function searchDocumentContent($query)
    {
        if (empty($this->document_content)) {
            return [];
        }

        $results = [];
        $content = $this->document_content;

        // Search through sections
        if (isset($content['sections'])) {
            foreach ($content['sections'] as $section) {
                if (stripos($section['title'] ?? '', $query) !== false ||
                    stripos($section['content'] ?? '', $query) !== false) {
                    $results[] = [
                        'type' => 'section',
                        'title' => $section['title'] ?? 'Untitled Section',
                        'content' => $section['content'] ?? '',
                        'relevance' => $this->calculateRelevance($query, $section['content'] ?? '')
                    ];
                }
            }
        }

        // Sort by relevance
        usort($results, function($a, $b) {
            return $b['relevance'] <=> $a['relevance'];
        });

        return array_slice($results, 0, 10); // Return top 10 results
    }

    private function calculateRelevance($query, $content)
    {
        $queryWords = explode(' ', strtolower($query));
        $contentLower = strtolower($content);

        $score = 0;
        foreach ($queryWords as $word) {
            $count = substr_count($contentLower, $word);
            $score += $count;
        }

        return $score;
    }
}
