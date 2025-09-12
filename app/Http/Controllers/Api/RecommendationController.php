<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Recommendations\RecommendationEngine;

class RecommendationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $results = RecommendationEngine::recommendForUser($user);
        return response()->json($results);
    }
}
