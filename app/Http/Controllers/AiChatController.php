<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiChatConversation;
use App\Models\ParentingModule;
use App\Models\ModuleContent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AiChatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get user's conversation sessions
        $sessions = AiChatConversation::forUser($user->id)
            ->select('session_id')
            ->distinct()
            ->orderBy('created_at', 'desc')
            ->get()
            ->pluck('session_id');

        // Get current session or create new one
        $currentSession = $request->get('session') ?: AiChatConversation::createNewSession($user->id);

        // Get conversation history for current session
        $conversation = AiChatConversation::bySession($currentSession)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('ai-chat.index', compact('sessions', 'currentSession', 'conversation'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'required|string',
        ]);

        $user = Auth::user();
        $message = trim($request->message);
        $sessionId = $request->session_id;

        // Save user message
        AiChatConversation::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'message_type' => 'user',
            'message' => $message,
        ]);

        // Generate AI response based on approved resources
        $response = $this->generateMentalHealthResponse($message, $user);

        // Save assistant response
        $assistantMessage = AiChatConversation::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'message_type' => 'assistant',
            'message' => $response['message'],
            'metadata' => $response['metadata'],
        ]);

        // Generate audio if requested
        if ($request->has('generate_audio') && $request->generate_audio) {
            $audioUrl = $this->generateAudio($response['message'], $assistantMessage->id);
            if ($audioUrl) {
                $assistantMessage->update([
                    'is_audio_generated' => true,
                    'audio_url' => $audioUrl,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => $assistantMessage,
            'sources' => $response['sources'] ?? [],
        ]);
    }

    public function generateAudio($messageId)
    {
        $message = AiChatConversation::findOrFail($messageId);

        if (!$message->isAssistantMessage()) {
            return response()->json(['error' => 'Only assistant messages can generate audio'], 400);
        }

        // Generate audio using Web Speech API or external service
        // For now, we'll simulate this - in production you'd integrate with TTS service
        $audioUrl = $this->generateAudioFile($message->message, $message->id);

        if ($audioUrl) {
            $message->update([
                'is_audio_generated' => true,
                'audio_url' => $audioUrl,
            ]);

            return response()->json([
                'success' => true,
                'audio_url' => $audioUrl,
            ]);
        }

        return response()->json(['error' => 'Failed to generate audio'], 500);
    }

    public function newSession()
    {
        $user = Auth::user();
        $sessionId = AiChatConversation::createNewSession($user->id);

        return response()->json([
            'success' => true,
            'session_id' => $sessionId,
        ]);
    }

    public function getConversation($sessionId)
    {
        $user = Auth::user();

        $conversation = AiChatConversation::bySession($sessionId)
            ->forUser($user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'conversation' => $conversation,
        ]);
    }

    private function generateMentalHealthResponse($userMessage, User $user)
    {
        // Search through approved parenting modules and content
        $searchResults = $this->searchApprovedResources($userMessage);

        // Generate contextual response based on search results
        $response = $this->createContextualResponse($userMessage, $searchResults, $user);

        return [
            'message' => $response,
            'metadata' => [
                'sources' => $searchResults['sources'],
                'confidence' => $searchResults['confidence'],
                'search_terms' => $this->extractKeywords($userMessage),
            ],
            'sources' => $searchResults['sources'],
        ];
    }

    private function searchApprovedResources($query)
    {
        $keywords = $this->extractKeywords($query);
        $sources = [];
        $confidence = 0;

        // Search in parenting modules
        foreach ($keywords as $keyword) {
            $modules = ParentingModule::published()
                ->where(function($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%")
                      ->orWhere('tags', 'like', "%{$keyword}%");
                })
                ->get();

            foreach ($modules as $module) {
                $sources[] = [
                    'type' => 'module',
                    'id' => $module->id,
                    'title' => $module->title,
                    'url' => route('parenting-modules.show', $module),
                    'relevance' => $this->calculateRelevance($keyword, $module),
                ];
                $confidence = max($confidence, $this->calculateRelevance($keyword, $module));
            }

            // Search in module contents
            $contents = ModuleContent::whereHas('module', function($q) {
                    $q->published();
                })
                ->where(function($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%");
                })
                ->with('module')
                ->get();

            foreach ($contents as $content) {
                $sources[] = [
                    'type' => 'content',
                    'id' => $content->id,
                    'title' => $content->title,
                    'module_title' => $content->module->title,
                    'url' => route('parenting-modules.content', [$content->module, $content]),
                    'relevance' => $this->calculateRelevance($keyword, $content),
                ];
                $confidence = max($confidence, $this->calculateRelevance($keyword, $content));
            }
        }

        // Remove duplicates and sort by relevance
        $sources = collect($sources)->unique('id')->sortByDesc('relevance')->take(5)->values()->all();

        return [
            'sources' => $sources,
            'confidence' => min(100, $confidence * 100),
        ];
    }

    private function createContextualResponse($userMessage, $searchResults, User $user)
    {
        $sources = $searchResults['sources'];
        $confidence = $searchResults['confidence'];

        // Create personalized response based on search results
        if (empty($sources)) {
            return "I understand you're looking for information about parenting and mental health. While I don't have specific resources that match your exact query right now, I recommend exploring our comprehensive parenting modules. You can find helpful information on child development, emotional intelligence, and positive parenting techniques. Would you like me to suggest some general resources or help you with a different topic?";
        }

        $response = "Based on our approved parenting and mental health resources, here's what I found relevant to your question:\n\n";

        foreach (array_slice($sources, 0, 3) as $source) {
            if ($source['type'] === 'module') {
                $response .= "📚 **{$source['title']}**\n";
                $response .= "This comprehensive module covers important aspects of parenting that relate to your question.\n\n";
            } else {
                $response .= "📖 **{$source['title']}** (from {$source['module_title']})\n";
                $response .= "This content piece provides specific information that addresses your topic.\n\n";
            }
        }

        if (count($sources) > 3) {
            $response .= "And " . (count($sources) - 3) . " more relevant resources...\n\n";
        }

        $response .= "💡 **Recommendation**: I suggest starting with the most relevant module above. Each module is designed to provide comprehensive, evidence-based information to support your parenting journey.\n\n";

        $response .= "Remember, while these resources provide valuable information, they're not a substitute for professional mental health advice. If you're dealing with specific mental health concerns, please consult with a qualified healthcare professional.";

        return $response;
    }

    private function extractKeywords($message)
    {
        // Simple keyword extraction - in production, use NLP library
        $message = strtolower($message);

        // Common parenting and mental health keywords
        $keywords = [
            'anxiety', 'depression', 'stress', 'parenting', 'child', 'baby', 'toddler',
            'development', 'behavior', 'discipline', 'emotion', 'mental health',
            'sleep', 'feeding', 'development', 'milestone', 'tantrum', 'crying',
            'attachment', 'bonding', 'communication', 'social skills', 'learning'
        ];

        $foundKeywords = [];
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                $foundKeywords[] = $keyword;
            }
        }

        // Also extract words that are likely to be important
        $words = str_word_count($message, 1);
        foreach ($words as $word) {
            if (strlen($word) > 4) { // Longer words are likely more specific
                $foundKeywords[] = $word;
            }
        }

        return array_unique($foundKeywords);
    }

    private function calculateRelevance($keyword, $resource)
    {
        $score = 0;
        $keyword = strtolower($keyword);

        // Check title relevance
        if (isset($resource->title) && strpos(strtolower($resource->title), $keyword) !== false) {
            $score += 0.4;
        }

        // Check description relevance
        if (isset($resource->description) && strpos(strtolower($resource->description), $keyword) !== false) {
            $score += 0.3;
        }

        // Check tags relevance
        if (isset($resource->tags) && is_array($resource->tags)) {
            foreach ($resource->tags as $tag) {
                if (strpos(strtolower($tag), $keyword) !== false) {
                    $score += 0.3;
                    break;
                }
            }
        }

        return min(1.0, $score);
    }

    private function generateAudioFile($text, $messageId)
    {
        // In production, integrate with a TTS service like:
        // - Google Text-to-Speech
        // - Amazon Polly
        // - Azure Speech Services
        // - ElevenLabs

        // For now, we'll simulate audio generation
        // You would replace this with actual TTS API calls

        try {
            // Simulate audio file generation
            $filename = 'audio_' . $messageId . '_' . time() . '.mp3';
            $path = 'ai-chat/audio/' . $filename;

            // In production, this would be the actual TTS API call
            // For demo purposes, we'll just return a placeholder URL
            $audioUrl = '/storage/' . $path;

            return $audioUrl;
        } catch (\Exception $e) {
            \Log::error('Audio generation failed: ' . $e->getMessage());
            return null;
        }
    }
}
