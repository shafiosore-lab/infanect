<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentingModule;
use App\Models\ModuleContent;
use App\Models\UserModuleProgress;
use App\Models\AiRecommendation;
use Illuminate\Support\Facades\Auth;

class ParentingModuleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ParentingModule::published()->forUser($user);

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
        $sortOrder = $request->get('order', 'desc');

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
        $categories = ParentingModule::published()
            ->distinct('category')
            ->pluck('category')
            ->filter()
            ->sort();

        // Get difficulty levels
        $difficulties = ParentingModule::published()
            ->distinct('difficulty_level')
            ->pluck('difficulty_level')
            ->filter()
            ->sort();

        return view('parenting-modules.index', compact(
            'modules',
            'userProgress',
            'categories',
            'difficulties',
            'request'
        ));
    }

    public function show(ParentingModule $module)
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

        // Get module contents
        $contents = $module->contents()->ordered()->get();

        // Get user's progress on contents
        $contentProgress = UserModuleProgress::where('user_id', $user->id)
            ->whereIn('content_id', $contents->pluck('id'))
            ->get()
            ->keyBy('content_id');

        // Get AI recommendations for this user
        $aiRecommendations = AiRecommendation::where('user_id', $user->id)
            ->active()
            ->where('module_id', '!=', $module->id)
            ->orderBy('confidence_score', 'desc')
            ->limit(3)
            ->get();

        return view('parenting-modules.show', compact(
            'module',
            'userProgress',
            'contents',
            'contentProgress',
            'aiRecommendations'
        ));
    }

    public function content(ParentingModule $module, ModuleContent $content)
    {
        $user = Auth::user();

        // Check if user can access this module
        if (!$module->published || !$module->canAccessBy($user)) {
            abort(403, 'You do not have access to this module.');
        }

        // Check if content belongs to module
        if ($content->module_id !== $module->id) {
            abort(404);
        }

        // Check if user can access this content
        if (!$content->canAccessBy($user)) {
            abort(403, 'You do not have access to this content.');
        }

        // Get or create progress for this content
        $contentProgress = UserModuleProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'module_id' => $module->id,
                'content_id' => $content->id,
            ],
            [
                'status' => 'in_progress',
                'progress_percentage' => 0,
            ]
        );

        // Mark as started
        $contentProgress->markAsStarted();

        // Get next and previous content
        $nextContent = ModuleContent::where('module_id', $module->id)
            ->where('order', '>', $content->order)
            ->orderBy('order')
            ->first();

        $previousContent = ModuleContent::where('module_id', $module->id)
            ->where('order', '<', $content->order)
            ->orderBy('order', 'desc')
            ->first();

        return view('parenting-modules.content', compact(
            'module',
            'content',
            'contentProgress',
            'nextContent',
            'previousContent'
        ));
    }

    public function updateProgress(Request $request, ParentingModule $module)
    {
        $request->validate([
            'content_id' => 'nullable|exists:module_contents,id',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'time_spent' => 'nullable|integer|min:0',
        ]);

        $user = Auth::user();

        $progressData = [
            'user_id' => $user->id,
            'module_id' => $module->id,
            'progress_percentage' => $request->progress_percentage,
        ];

        if ($request->content_id) {
            $progressData['content_id'] = $request->content_id;
        }

        $progress = UserModuleProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'module_id' => $module->id,
                'content_id' => $request->content_id ?? null,
            ],
            $progressData
        );

        // Update time spent if provided
        if ($request->time_spent) {
            $progress->addTimeSpent($request->time_spent);
        }

        // Mark as completed if 100%
        if ($request->progress_percentage >= 100) {
            $progress->markAsCompleted();
        }

        return response()->json([
            'success' => true,
            'progress' => $progress
        ]);
    }

    public function toggleFavorite(ParentingModule $module)
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

    public function rate(Request $request, ParentingModule $module)
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

        $progress = UserModuleProgress::with(['module', 'currentContent'])
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

        return view('parenting-modules.my-progress', compact('progress', 'stats'));
    }

    public function favorites()
    {
        $user = Auth::user();

        $favorites = UserModuleProgress::with('module')
            ->where('user_id', $user->id)
            ->where('is_favorited', true)
            ->orderBy('last_accessed_at', 'desc')
            ->paginate(12);

        return view('parenting-modules.favorites', compact('favorites'));
    }

    public function recommendations()
    {
        $user = Auth::user();

        // Generate new recommendations if needed
        $existingCount = AiRecommendation::where('user_id', $user->id)->active()->count();

        if ($existingCount < 5) {
            AiRecommendation::generateForUser($user, 'personalized', 5 - $existingCount);
        }

        $recommendations = AiRecommendation::with('module')
            ->where('user_id', $user->id)
            ->active()
            ->orderBy('confidence_score', 'desc')
            ->paginate(12);

        return view('parenting-modules.recommendations', compact('recommendations'));
    }
}
