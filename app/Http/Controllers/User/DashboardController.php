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

        return view('dashboards.user', [
            'user'              => $user,
            'stats'             => $this->getUserStats($user->id),
            'bondingActivities' => $this->getBondingActivities(),
            'parentingModules'  => $this->getParentingModules(),
            'allModules'        => $this->getAllModules(),
            'aiChats'           => $this->getAiChats(),
        ]);
    }

    /**
     * Get user booking statistics.
     */
    private function getUserStats(int $userId): array
    {
        return [
            'total_bookings' => Booking::where('user_id', $userId)->count(),
            'completed'      => Booking::where('user_id', $userId)->where('status', 'completed')->count(),
            'pending'        => Booking::where('user_id', $userId)->where('status', 'pending')->count(),
            'spent'          => Booking::where('user_id', $userId)->where('status', 'completed')->sum('amount'),
        ];
    }

    /**
     * Fetch bonding activities.
     */
    private function getBondingActivities()
    {
        return Service::where('category', 'bonding')
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * Fetch parenting modules.
     */
    private function getParentingModules()
    {
        return Module::where('type', 'parenting')
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * Fetch general learning/digital wellness modules.
     */
    private function getAllModules()
    {
        return Module::latest()
            ->take(10)
            ->get();
    }

    /**
     * Static list of AI chat assistants.
     */
    private function getAiChats(): array
    {
        return [
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
