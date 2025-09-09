<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingModule;
use App\Models\UserModuleProgress;
use App\Models\AiChatConversation;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TrainingModuleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = TrainingModule::published()->forUser($user);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filter by difficulty
        if ($request->has('difficulty') && $request->difficulty) {
            $query->where('difficulty_level', $request->difficulty);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('tags', 'like', '%' . $request->search . '%');
            });
        }

        // Sort options
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = in_array(strtolower($request->get('order', 'desc')), ['asc', 'desc'])
            ? strtolower($request->get('order', 'desc'))
            : 'desc';

        switch ($sortBy) {
            case 'title':
                $query->orderBy('title', $sortOrder);
                break;
            case 'rating':
                $query->orderBy('rating', $sortOrder);
                break;
            case 'duration':
                $query->orderBy('estimated_duration', $sortOrder);
                break;
            case 'popularity':
                $query->orderBy('view_count', $sortOrder);
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
        }

        $modules = $query->paginate(12);

        // Get user's progress for these modules
        $userProgress = UserModuleProgress::where('user_id', $user->id)
            ->whereIn('module_id', $modules->pluck('id'))
            ->get()
            ->keyBy('module_id');

        // Get categories for filter
        $categories = TrainingModule::published()
            ->distinct('category')
            ->pluck('category')
            ->filter()
            ->sort();

        // Get difficulty levels
        $difficulties = TrainingModule::published()
            ->distinct('difficulty_level')
            ->pluck('difficulty_level')
            ->filter()
            ->sort();

        return view('training-modules.index', compact(
            'modules',
            'userProgress',
            'categories',
            'difficulties',
            'request'
        ));
    }

    public function show(TrainingModule $module)
    {
        $user = Auth::user();

        // Check if user can access this module
        if (!$module->published || !$module->canAccessBy($user)) {
            abort(403, 'You do not have access to this module.');
        }

        // Increment view count
        $module->incrementViewCount();

        // Get or create user progress
        $userProgress = UserModuleProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'module_id' => $module->id,
            ],
            [
                'status' => 'not_started',
                'progress_percentage' => 0,
            ]
        );

        // Mark as started if not already
        if ($userProgress->status === 'not_started') {
            $userProgress->markAsStarted();
        }

        return view('training-modules.show', compact(
            'module',
            'userProgress'
        ));
    }

    public function chat(TrainingModule $module)
    {
        $user = Auth::user();

        // Check if user can access this module
        if (!$module->published || !$module->canAccessBy($user)) {
            abort(403, 'You do not have access to this module.');
        }

        // Check if AI chat is enabled
        if (!$module->canUseAiChat()) {
            abort(403, 'AI chat is not available for this module.');
        }

        // Get chat history
        $conversations = AiChatConversation::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse();

        return view('training-modules.chat', compact(
            'module',
            'conversations'
        ));
    }

    public function sendMessage(Request $request, TrainingModule $module)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Check if user can access this module
        if (!$module->published || !$module->canAccessBy($user)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        // Check if AI chat is enabled
        if (!$module->canUseAiChat()) {
            return response()->json(['error' => 'AI chat not available'], 403);
        }

        $userMessage = $request->message;

        // Search document content for relevant information
        $searchResults = $module->searchDocumentContent($userMessage);

        // Generate AI response using document context
        $aiService = app(AIService::class);
        $aiResponse = $aiService->generateChatResponse($userMessage, $searchResults, $module->document_content);

        // Save conversation
        AiChatConversation::create([
            'user_id' => $user->id,
            'module_id' => $module->id,
            'user_message' => $userMessage,
            'ai_response' => $aiResponse,
            'context_used' => $searchResults,
        ]);

        return response()->json([
            'success' => true,
            'response' => $aiResponse,
            'context' => $searchResults
        ]);
    }

    public function updateProgress(Request $request, TrainingModule $module)
    {
        $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
        ]);

        $user = Auth::user();

        $progress = UserModuleProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'module_id' => $module->id,
            ],
            [
                'progress_percentage' => $request->progress_percentage,
            ]
        );

        // Mark as completed if 100%
        if ($request->progress_percentage >= 100) {
            $progress->markAsCompleted();
        }

        return response()->json([
            'success' => true,
            'progress' => $progress
        ]);
    }

    public function toggleFavorite(TrainingModule $module)
    {
        $user = Auth::user();

        $progress = UserModuleProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'module_id' => $module->id,
            ],
            [
                'status' => 'not_started',
                'progress_percentage' => 0,
            ]
        );

        $progress->toggleFavorite();

        return response()->json([
            'success' => true,
            'is_favorited' => $progress->is_favorited
        ]);
    }

    public function rate(Request $request, TrainingModule $module)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();

        $progress = UserModuleProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'module_id' => $module->id,
            ],
            [
                'status' => 'not_started',
                'progress_percentage' => 0,
            ]
        );

        $progress->rate($request->rating);

        return response()->json([
            'success' => true,
            'rating' => $progress->rating
        ]);
    }

    public function myProgress()
    {
        $user = Auth::user();

        $progress = UserModuleProgress::with(['module'])
            ->where('user_id', $user->id)
            ->orderBy('last_accessed_at', 'desc')
            ->paginate(12);

        $stats = [
            'total_modules' => $progress->where('status', 'completed')->count(),
            'in_progress' => $progress->where('status', 'in_progress')->count(),
            'not_started' => $progress->where('status', 'not_started')->count(),
            'favorited' => $progress->where('is_favorited', true)->count(),
            'total_time_spent' => $progress->sum('time_spent'),
        ];

        return view('training-modules.my-progress', compact('progress', 'stats'));
    }

    public function favorites()
    {
        $user = Auth::user();

        $favorites = UserModuleProgress::with('module')
            ->where('user_id', $user->id)
            ->where('is_favorited', true)
            ->orderBy('last_accessed_at', 'desc')
            ->paginate(12);

        return view('training-modules.favorites', compact('favorites'));
    }

    // Admin methods for creating/managing training modules
    public function create()
    {
        $this->authorize('create', TrainingModule::class);

        return view('training-modules.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', TrainingModule::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'difficulty_level' => 'required|string',
            'estimated_duration' => 'required|integer|min:1',
            'document' => 'nullable|file|mimes:pdf,docx,txt|max:10240', // 10MB max
            'enable_ai_chat' => 'boolean',
            'is_premium' => 'boolean',
            'is_published' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        $data = $request->only([
            'title', 'description', 'category', 'difficulty_level',
            'estimated_duration', 'enable_ai_chat', 'is_premium', 'is_published'
        ]);

        $data['created_by'] = Auth::id();
        $data['tags'] = $request->tags ? json_decode($request->tags, true) : [];

        // Handle document upload
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $path = $file->store('training-documents', 'public');
            $data['document_path'] = $path;
            $data['document_type'] = $file->getClientOriginalExtension();

            // Extract content from document
            $module = TrainingModule::create($data);
            $module->extractDocumentContent();
        } else {
            $module = TrainingModule::create($data);
        }

        return redirect()->route('training-modules.show', $module)
            ->with('success', 'Training module created successfully!');
    }

    public function edit(TrainingModule $module)
    {
        $this->authorize('update', $module);

        return view('training-modules.edit', compact('module'));
    }

    public function update(Request $request, TrainingModule $module)
    {
        $this->authorize('update', $module);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'difficulty_level' => 'required|string',
            'estimated_duration' => 'required|integer|min:1',
            'document' => 'nullable|file|mimes:pdf,docx,txt|max:10240',
            'enable_ai_chat' => 'boolean',
            'is_premium' => 'boolean',
            'is_published' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        $data = $request->only([
            'title', 'description', 'category', 'difficulty_level',
            'estimated_duration', 'enable_ai_chat', 'is_premium', 'is_published'
        ]);

        $data['tags'] = $request->tags ? json_decode($request->tags, true) : [];

        // Handle document upload
        if ($request->hasFile('document')) {
            // Delete old document if exists
            if ($module->document_path) {
                Storage::disk('public')->delete($module->document_path);
            }

            $file = $request->file('document');
            $path = $file->store('training-documents', 'public');
            $data['document_path'] = $path;
            $data['document_type'] = $file->getClientOriginalExtension();

            $module->update($data);
            $module->extractDocumentContent();
        } else {
            $module->update($data);
        }

        return redirect()->route('training-modules.show', $module)
            ->with('success', 'Training module updated successfully!');
    }

    public function destroy(TrainingModule $module)
    {
        $this->authorize('delete', $module);

        // Delete document file if exists
        if ($module->document_path) {
            Storage::disk('public')->delete($module->document_path);
        }

        $module->delete();

        return redirect()->route('training-modules.index')
            ->with('success', 'Training module deleted successfully!');
    }
}
