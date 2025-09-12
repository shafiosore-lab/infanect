<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service as ProviderService;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = ProviderService::where('provider_id', $request->user()->provider?->id)->get();
        return view('providers.services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'currency' => 'nullable|string|size:3',
            'delivery_type' => 'nullable|in:online,offline',
        ]);

        $service = ProviderService::create(array_merge($data, ['provider_id' => $request->user()->provider?->id]));

        return redirect()->route('provider.services')->with('status', 'Service created');
    }
}
