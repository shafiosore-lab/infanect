<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Approval;
use App\Models\Activity;
use App\Models\Service;
use App\Models\ServiceProvider;
use App\Models\User;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = Approval::with(['requestor', 'approver']);

        // Filter by type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $approvals = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.approvals.index', compact('approvals'));
    }

    public function show(Approval $approval)
    {
        $approval->load(['requestor', 'approver', 'entity']);

        return view('admin.approvals.show', compact('approval'));
    }

    public function approve(Request $request, Approval $approval)
    {
        $request->validate([
            'comments' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        if ($request->has('approved_data')) {
            // Admin has modified the data
            $approval->approved_data = $request->approved_data;
        } else {
            // Use original request data
            $approval->approved_data = $approval->request_data;
        }

        $approval->approve($user, $request->comments);

        return redirect()->route('admin.approvals.index')->with('success', 'Request approved successfully.');
    }

    public function reject(Request $request, Approval $approval)
    {
        $request->validate([
            'comments' => 'required|string|max:1000',
        ]);

        $user = auth()->user();
        $approval->reject($user, $request->comments);

        return redirect()->route('admin.approvals.index')->with('success', 'Request rejected.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'approvals' => 'required|array',
            'approvals.*' => 'exists:approvals,id',
            'action' => 'required|in:approve,reject',
            'comments' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        $count = 0;

        foreach ($request->approvals as $approvalId) {
            $approval = Approval::find($approvalId);

            if ($approval && $approval->status === 'pending') {
                if ($request->action === 'approve') {
                    $approval->approved_data = $approval->request_data;
                    $approval->approve($user, $request->comments);
                } else {
                    $approval->reject($user, $request->comments);
                }
                $count++;
            }
        }

        return redirect()->route('admin.approvals.index')
                        ->with('success', "{$count} requests {$request->action}d successfully.");
    }
}
