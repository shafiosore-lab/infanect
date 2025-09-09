<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;
use App\Events\NewContentCreated;

class ServicesController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Check if user can list services
        if (!$user->canListServices()) {
            abort(403, 'You do not have permission to view services.');
        }

        $query = Service::with(['user', 'category'])->active();

        // Service Providers can only see their own services
        if ($user->isServiceProvider()) {
            $query->where('user_id', $user->id);
        }

        $services = $query->search($request->search)
            ->category($request->category)
            ->sortBy($request->sort, $request->direction)
            ->paginate(12);

        $categories = Category::all();

        return view('services.index', compact('services', 'categories'));
    }

    /**
     * Display popular services.
     */
    public function popular(Request $request)
    {
        $services = Service::with(['user', 'category'])
            ->active()
            ->popular() // assumes a scopePopular exists
            ->paginate(12);

        $categories = Category::all();

        return view('services.popular', compact('services', 'categories'));
    }

    /**
     * Display services by category.
     */
    public function categories(Request $request)
    {
        $services = Service::with(['user', 'category'])
            ->active()
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->paginate(12);

        $categories = Category::all();
        $selectedCategory = $request->category ? Category::find($request->category) : null;

        return view('services.categories', compact('services', 'categories', 'selectedCategory'));
    }

    /**
     * Show a single service.
     */
    public function show(Service $service)
    {
        $service->load(['user', 'category']);
        return view('services.show', compact('service'));
    }

    /**
     * Store a newly created service and broadcast update.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'price'       => 'nullable|numeric|min:0',
            'provider_id' => 'required|exists:providers,id',
        ]);

        $service = Service::create($validated)->load(['user', 'category']);

        // Render only the new service card fragment
        $html = view('partials.activities-services-providers', [
            'activities' => collect(),
            'services'   => collect([$service]),
            'providers'  => collect(),
        ])->render();

        // Broadcast via Laravel Echo
        broadcast(new NewContentCreated($html))->toOthers();

        return redirect()->route('services.index')
            ->with('success', 'Service created successfully and broadcasted!');
    }
}
