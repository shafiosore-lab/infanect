<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate(['locale' => 'nullable|string', 'currency' => 'nullable|string']);
        foreach ($data as $key => $value) {
            DB::table('settings')->updateOrInsert(['key' => $key], ['value' => $value]);
        }
        return redirect()->back()->with('status', 'Settings saved');
    }
}
