<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\App;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FeedbackController extends Controller
{
    /**
     * Display a listing of client feedback.
     */
    public function index(Request $request)
    {
        // Set locale for internationalization (future-ready)
        App::setLocale($request->get('locale', 'en'));

        // ✅ DB-ready (uncomment when Feedback model/table exists)
        /*
        $query = Feedback::query();

        if ($request->filled('user')) {
            $query->where('user_name', 'like', '%' . $request->user . '%');
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $feedbacks = $query->latest()->paginate(20);
        */

        // ✅ Temporary demo data (converted to a paginator & object-like for Blade)
        $demoData = [
            (object)['user_name' => 'John Doe', 'message' => 'Excellent service and friendly staff!', 'rating' => 5, 'created_at' => now()],
            (object)['user_name' => 'Jane Smith', 'message' => 'Booking process could be improved.', 'rating' => 3, 'created_at' => now()->subDays(1)],
            (object)['user_name' => 'Ali Ahmed', 'message' => 'Great international support!', 'rating' => 4, 'created_at' => now()->subDays(2)],
        ];

        $feedbacks = new LengthAwarePaginator(
            collect($demoData)->forPage($request->page ?? 1, 10),
            count($demoData),
            10,
            $request->page ?? 1,
            ['path' => url()->current()]
        );

        return view('admin.feedback.index', compact('feedbacks'));
    }
}
