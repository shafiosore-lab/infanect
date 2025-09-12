<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EngagementController extends Controller
{
    public function index()
    {
        $engagements = $this->generateEngagements();

        return view('engagements.index', compact('engagements'));
    }

    public function show($id)
    {
        $engagement = $this->getEngagementById($id);

        if (!$engagement) {
            abort(404);
        }

        return view('engagements.show', compact('engagement'));
    }

    public function join(Request $request, $id)
    {
        $engagement = $this->getEngagementById($id);

        if (!$engagement) {
            abort(404);
        }

        // In a real application, you would save to database

        return redirect()->route('engagements.show', $id)
                        ->with('success', 'You have successfully joined this engagement!');
    }

    private function generateEngagements()
    {
        return collect([
            (object)[
                'id' => 1,
                'title' => 'Family Photo Contest',
                'description' => 'Share your best family moments from activities and win amazing prizes!',
                'type' => 'contest',
                'participants' => 124,
                'max_participants' => 500,
                'start_date' => '2024-02-01',
                'end_date' => '2024-02-29',
                'prize' => 'Free family activity package worth $500',
                'status' => 'active',
                'image' => 'contest-photo.jpg',
                'requirements' => [
                    'Photo must be from an Infanect activity',
                    'Include all family members',
                    'High resolution (min 1080p)',
                    'Submit with activity details'
                ]
            ],
            (object)[
                'id' => 2,
                'title' => 'Weekly Family Challenge',
                'description' => 'Complete weekly challenges as a family and earn reward points!',
                'type' => 'challenge',
                'participants' => 89,
                'max_participants' => 200,
                'start_date' => '2024-01-29',
                'end_date' => '2024-02-05',
                'prize' => '100 reward points per completed challenge',
                'status' => 'active',
                'image' => 'weekly-challenge.jpg',
                'requirements' => [
                    'Complete as a family unit',
                    'Submit proof of completion',
                    'Share on social media (optional)'
                ]
            ],
            (object)[
                'id' => 3,
                'title' => 'Community Service Day',
                'description' => 'Join other families in giving back to the community through various service activities.',
                'type' => 'community',
                'participants' => 45,
                'max_participants' => 100,
                'start_date' => '2024-02-10',
                'end_date' => '2024-02-10',
                'prize' => 'Certificate of appreciation and community impact badge',
                'status' => 'upcoming',
                'image' => 'community-service.jpg',
                'requirements' => [
                    'Minimum 2 hours participation',
                    'All ages welcome',
                    'Bring water and snacks',
                    'Wear comfortable clothes'
                ]
            ],
        ]);
    }

    private function getEngagementById($id)
    {
        return $this->generateEngagements()->firstWhere('id', $id);
    }
}
