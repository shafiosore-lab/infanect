<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChatConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'message_type',
        'message',
        'metadata',
        'is_audio_generated',
        'audio_url',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_audio_generated' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeUserMessages($query)
    {
        return $query->where('message_type', 'user');
    }

    public function scopeAssistantMessages($query)
    {
        return $query->where('message_type', 'assistant');
    }

    public function scopeWithAudio($query)
    {
        return $query->where('is_audio_generated', true);
    }

    /**
     * Helper Methods
     */
    public function isUserMessage()
    {
        return $this->message_type === 'user';
    }

    public function isAssistantMessage()
    {
        return $this->message_type === 'assistant';
    }

    public function hasAudio()
    {
        return $this->is_audio_generated && $this->audio_url;
    }

    public function getSources()
    {
        return $this->metadata['sources'] ?? [];
    }

    public function getConfidence()
    {
        return $this->metadata['confidence'] ?? null;
    }

    /**
     * Static Methods
     */
    public static function getConversationHistory($sessionId, $limit = 50)
    {
        return self::bySession($sessionId)
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
    }

    public static function createNewSession($userId)
    {
        return uniqid('chat_' . $userId . '_', true);
    }
}
