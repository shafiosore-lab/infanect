<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Display the user dashboard with stats, bonding activities, modules, and AI chat assistants.
     */
    public function index()
    {
        $user = Auth::user();

        // User booking statistics
        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'completed'      => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            'pending'        => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'spent'          => Booking::where('user_id', $user->id)->where('status', 'completed')->sum('amount'),
        ];

        // Bonding activities (services/events available)
        $bondingActivities = Service::where('category', 'bonding')
            ->latest()
            ->take(5)
            ->get();

        // Parenting modules (learning content)
        $parentingModules = Module::where('type', 'parenting')
            ->latest()
            ->take(5)
            ->get();

        // All modules (general learning/digital wellness)
        $allModules = Module::latest()
            ->take(10)
            ->get();

        // AI Chats (static for now, but could be from DB or API later)
        $aiChats = [
            [
                'title'       => 'Family Wellbeing Assistant',
                'description' => 'Chat with AI to explore family digital wellness strategies.',
                'link'        => route('ai.chat', ['assistant' => 'wellbeing']),
            ],
            [
                'title'       => 'Parenting AI Coach',
                'description' => 'Get instant parenting tips tailored to your child’s age.',
                'link'        => route('ai.chat', ['assistant' => 'parenting']),
            ],
        ];

        return view('dashboards.user', compact(
            'user',
            'stats',
            'bondingActivities',
            'parentingModules',
            'allModules',
            'aiChats'
        ));
    }

    /**
     * Weekly engagement data (demo JSON, replace with DB aggregation later).
     */
    public function weeklyEngagement()
    {
        return response()->json([
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'data'   => [30, 45, 20, 60, 50, 75, 40], // Minutes per day
        ]);
    }

    /**
     * Learning progress data (demo JSON, replace with DB aggregation later).
     */
    public function learningProgress()
    {
        return response()->json([
            'labels' => ['Parenting', 'Training', 'AI Chat', 'Bonding'],
            'data'   => [80, 65, 90, 70], // % completion
        ]);
    }
}
