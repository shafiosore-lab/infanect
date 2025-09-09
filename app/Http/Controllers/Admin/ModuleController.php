<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $modules = Module::paginate(10);
        return view('admin.modules.index', compact('modules'));
    }

    public function create()
    {
        return view('admin.modules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        // Convert comma-separated tags to array
        if ($request->tags) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        }

        Module::create($validated);

        return redirect()->route('modules.index')
            ->with('success', 'Module created successfully!');
    }

    public function show(Module $module)
    {
        return view('admin.modules.show', compact('module'));
    }

    public function edit(Module $module)
    {
        return view('admin.modules.edit', compact('module'));
    }

    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        // Convert comma-separated tags to array
        if ($request->tags) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        } else {
            $validated['tags'] = [];
        }

        $module->update($validated);

        return redirect()->route('modules.index')
            ->with('success', 'Module updated successfully!');
    }

    public function destroy(Module $module)
    {
        $module->delete();

        return redirect()->route('modules.index')
            ->with('success', 'Module deleted successfully!');
    }
}
