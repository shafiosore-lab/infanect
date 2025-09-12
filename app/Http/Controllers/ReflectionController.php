<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ReflectionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'lesson_external_id' => 'required|string',
            'helpful' => 'nullable|in:0,1',
            'notes' => 'nullable|string|max:2000',
        ]);

        $payload = [
            'user_id' => auth()->id(),
            'lesson_external_id' => $request->input('lesson_external_id'),
            'helpful' => $request->has('helpful') ? (int)$request->input('helpful') : null,
            'notes' => $request->input('notes'),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        try {
            if (Schema::hasTable('reflections')) {
                DB::table('reflections')->insert($payload);
                return redirect()->back()->with('status', 'Thanks for your feedback.');
            }

            // Fallback: store in cache for now
            cache()->push('infaNect_reflections_pending', $payload);
            return redirect()->back()->with('status', 'Saved locally (no reflections table found).');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Unable to save reflection.');
        }
    }
}
