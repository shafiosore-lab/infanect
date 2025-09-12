<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::latest()->paginate(12);
        return view('resources.index', compact('resources'));
    }

    public function show(Resource $resource)
    {
        return view('resources.show', compact('resource'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string'
        ]);

        Resource::create(array_merge($data, ['user_id' => $request->user()->id ?? null]));

        return redirect()->route('resources.index')->with('status', 'Resource published');
    }
}
