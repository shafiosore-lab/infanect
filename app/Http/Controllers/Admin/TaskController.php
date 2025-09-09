<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = Task::with(['assignedUser', 'creator'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        $users = User::select('id', 'name')->get();

        return view('admin.tasks.index', compact('tasks', 'users'));
    }

    public function create(): View
    {
        $users = User::select('id', 'name')->get();
        return view('admin.tasks.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date|after:today',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $validated['created_by'] = auth()->id();

        Task::create($validated);

        return redirect()->route('admin.tasks.index')
                        ->with('success', 'Task created successfully.');
    }

    public function show(Task $task): View
    {
        $task->load(['assignedUser', 'creator']);
        return view('admin.tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        $users = User::select('id', 'name')->get();
        return view('admin.tasks.edit', compact('task', 'users'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task->update($validated);

        return redirect()->route('admin.tasks.index')
                        ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')
                        ->with('success', 'Task deleted successfully.');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $task->update(['status' => $request->status]);

        return redirect()->back()
                        ->with('success', 'Task status updated successfully.');
    }
}
