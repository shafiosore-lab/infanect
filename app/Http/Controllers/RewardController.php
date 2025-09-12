<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = $this->generateRewards();
        $userPoints = 250;

        return view('rewards.index', compact('rewards', 'userPoints'));
    }

    public function redeem(Request $request, $id)
    {
        // In a real application, you would process the redemption
        return redirect()->route('rewards.index')
                        ->with('success', 'Reward redeemed successfully!');
    }

    public function history()
    {
        $history = $this->generatePointsHistory();

        return view('rewards.history', compact('history'));
    }

    private function generateRewards()
    {
        return collect([
            (object)[
                'id' => 1,
                'title' => '10% Off Next Service',
                'description' => 'Get 10% discount on your next service booking',
                'points_required' => 100,
                'category' => 'discount',
                'expires_at' => '2024-03-31',
                'available' => true
            ],
            (object)[
                'id' => 2,
                'title' => 'Free Activity for Kids',
                'description' => 'One free kids activity (up to $50 value)',
                'points_required' => 200,
                'category' => 'free_activity',
                'expires_at' => '2024-04-30',
                'available' => true
            ],
            (object)[
                'id' => 3,
                'title' => 'Premium Membership Month',
                'description' => 'One month of premium membership benefits',
                'points_required' => 500,
                'category' => 'premium',
                'expires_at' => '2024-05-31',
                'available' => false
            ],
        ]);
    }

    private function generatePointsHistory()
    {
        return collect([
            (object)[
                'date' => '2024-01-20',
                'description' => 'Activity booking bonus',
                'points' => 50,
                'type' => 'earned'
            ],
            (object)[
                'date' => '2024-01-18',
                'description' => 'Service booking',
                'points' => 25,
                'type' => 'earned'
            ],
            (object)[
                'date' => '2024-01-15',
                'description' => 'Redeemed 10% discount',
                'points' => -100,
                'type' => 'spent'
            ],
        ]);
    }
}
