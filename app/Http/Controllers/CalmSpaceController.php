<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalmSpaceController extends Controller
{
    public function track(Request $request)
    {
        $type = $request->input('type'); // 'breathing' or 'coping'
        $counts = $request->session()->get('calmspace_counts', []);
        $counts[$type] = ($counts[$type] ?? 0) + 1;
        $request->session()->put('calmspace_counts', $counts);
        return response()->json(['status' => 'ok', 'counts' => $counts]);
    }

    public function resources()
    {
        return view('resources.mental-health');
    }
}
