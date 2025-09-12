<?php

namespace App\Services\Recommendations;

use App\Models\Activity;
use App\Models\ActivityPreference;
use App\Models\Provider;
use App\Models\ActivityTemplate;

class RecommendationEngine
{
    public static function recommendForUser($user)
    {
        if (!$user) return [];

        // simple rule-based recommender
        $pref = ActivityPreference::where('user_id', $user->id)->first();
        $preferred = $pref?->preferences ?? [];

        // match activity templates first
        $query = ActivityTemplate::query();
        if (!empty($preferred['category'])) {
            $query->where('tags', 'like', '%'.addslashes($preferred['category']).'%');
        }
        if (!empty($preferred['age_group'])) {
            $query->whereJsonContains('age_groups', $preferred['age_group']);
        }

        $templates = $query->limit(10)->get();
        if ($templates->isNotEmpty()) return $templates;

        // fallback to Activity model
        $actQuery = Activity::query();
        if (!empty($preferred['category'])) {
            $actQuery->where('category', $preferred['category']);
        }
        if (!empty($preferred['age_group'])) {
            $actQuery->whereJsonContains('meta->age_groups', $preferred['age_group']);
        }

        return $actQuery->limit(10)->get();
    }
}
