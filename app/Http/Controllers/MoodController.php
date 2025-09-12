<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoodSubmission;
use App\Jobs\RecommendationJob;

class MoodController extends Controller
{
    public function submit(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'mood' => 'required|string',
            'mood_score' => 'nullable|integer|min:0|max:10',
            'availability' => 'required|array',
            'timezone' => 'nullable|string',
            'location' => 'nullable|array',
            'age_group' => 'nullable|string',
            'language' => 'nullable|string'
        ]);

        $mood = MoodSubmission::create([
            'user_id' => $user->id,
            'mood' => $data['mood'],
            'mood_score' => $data['mood_score'] ?? null,
            'availability' => $data['availability'],
            'location' => $data['location'] ?? null,
            'timezone' => $data['timezone'] ?? null,
            'language' => $data['language'] ?? null
        ]);

        RecommendationJob::dispatch($mood->id, $user->id);

        return response()->json(['status' => 'queued','mood_id' => $mood->id], 202);
    }
}
