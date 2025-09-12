<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\MoodSubmission;
use App\Models\Recommendation;
use App\Models\ActivityTemplate;
use App\Models\AudioModule;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RecommendationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $moodId;
    protected $userId;

    public function __construct($moodId, $userId)
    {
        $this->moodId = $moodId;
        $this->userId = $userId;
    }

    public function handle()
    {
        $mood = MoodSubmission::find($this->moodId);
        if (! $mood) return;

        // Map mood to tags
        $moodMap = [
            'stressed' => ['calming','low-energy','sensory','indoor'],
            'adventurous' => ['outdoor','high-energy','discovery'],
            'bored' => ['creative','short','novelty'],
            'happy' => ['celebration','social','creative'],
            'tired' => ['restful','low-energy','gentle']
        ];

        $tags = $moodMap[$mood->mood] ?? [$mood->mood];

        // Base candidate templates
        $query = ActivityTemplate::query();
        $query->where('locale', $mood->language ?? 'en');

        // Filter by tag matches (JSON array 'tags')
        $query->where(function($q) use ($tags) {
            foreach ($tags as $t) {
                $q->orWhereJsonContains('tags', $t);
            }
        });

        // If location provided, attempt to rank providers by proximity
        $location = $mood->location; // expected ['lat'=>..., 'lng'=>...]
        $candidates = $query->inRandomOrder()->limit(30)->get();

        $providers = [];
        // Build provider list; include distance if possible
        if (! empty($location) && is_array($location) && isset($location['lat'], $location['lng'])) {
            $lat = (float) $location['lat'];
            $lng = (float) $location['lng'];

            // Attempt to find provider users with latitude/longitude columns
            try {
                $nearbyProviders = User::selectRaw("users.*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$lat, $lng, $lat])
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->orderBy('distance', 'asc')
                    ->limit(20)
                    ->get();

                foreach ($nearbyProviders as $p) {
                    $providers[$p->id] = ['id' => $p->id, 'name' => $p->name, 'distance_km' => round($p->distance,1), 'profile_url' => route('providers.show', $p->id)];
                }
            } catch (\Exception $e) {
                // columns may not exist - fallback
            }
        }

        // Ensure providers from templates are included
        foreach ($candidates as $c) {
            if ($c->provider_id) {
                $p = User::find($c->provider_id);
                if ($p && ! isset($providers[$p->id])) {
                    $providers[$p->id] = ['id' => $p->id, 'name' => $p->name, 'profile_url' => route('providers.show', $p->id)];
                }
            }
        }

        // If still empty, include some random providers (limit 5)
        if (empty($providers)) {
            $randProviders = User::whereHas('roles', function($q){ $q->where('slug','provider'); })->inRandomOrder()->limit(5)->get();
            foreach ($randProviders as $p) {
                $providers[$p->id] = ['id' => $p->id, 'name' => $p->name, 'profile_url' => route('providers.show', $p->id)];
            }
        }

        // Build activities payload from top templates (limit 3)
        $activities = $candidates->take(3)->map(function($a){
            return ['id'=>$a->id,'title'=>$a->title,'description'=>$a->description,'duration'=>$a->duration,'tags'=>$a->tags,'provider_id'=>$a->provider_id];
        })->values()->all();

        $providersArr = array_values($providers);

        // Prepare audio text summary
        $titles = collect($activities)->pluck('title')->join(', ');
        $audioText = "Here are some quick activities for your family: " . $titles;

        $payload = [
            'activities' => $activities,
            'providers' => $providersArr,
            'audio_text' => $audioText
        ];

        $rec = Recommendation::create([
            'user_id' => $this->userId,
            'mood_submission_id' => $this->moodId,
            'payload' => $payload,
            'score' => 0.5,
            'generated_at' => now()
        ]);

        // Optionally dispatch TTS and sending jobs here
    }
}
