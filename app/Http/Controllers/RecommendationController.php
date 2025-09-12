<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recommendation;

class RecommendationController extends Controller
{
    public function show($id)
    {
        $rec = Recommendation::with('moodSubmission')->findOrFail($id);
        return response()->json($rec);
    }

    public function confirm(Request $request, $id)
    {
        $rec = Recommendation::findOrFail($id);
        $rec->update(['status' => 'confirmed']);
        return response()->json(['status' => 'confirmed']);
    }
}
