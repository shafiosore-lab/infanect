<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    /**
     * Display a listing of subscriptions.
     */
    public function index(Request $request)
    {
        $query = Subscription::query();

        // 🔍 Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }
        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        $subscriptions = $query->latest()->paginate(10);

        // Summary cards
        $totalSubscriptions   = Subscription::count();
        $activeSubscriptions  = Subscription::active()->count();
        $expiredSubscriptions = Subscription::expired()->count();
        $trialSubscriptions   = Subscription::trial()->count();
        $expiringSoon         = Subscription::whereNotNull('expires_at')
                                    ->whereBetween('expires_at', [now(), now()->addDays(7)])
                                    ->count();

        // Chart data
        $monthlyLabels = collect(range(0, 11))
            ->map(fn ($i) => now()->subMonths($i)->format('M Y'))
            ->reverse()
            ->values();

        $monthlyValues = $monthlyLabels->map(function ($label) {
            return Subscription::whereMonth('created_at', '=', date('m', strtotime($label)))
                               ->whereYear('created_at', '=', date('Y', strtotime($label)))
                               ->count();
        });

        return view('admin.subscriptions.index', compact(
            'subscriptions',
            'totalSubscriptions',
            'activeSubscriptions',
            'expiredSubscriptions',
            'trialSubscriptions',
            'expiringSoon',
            'monthlyLabels',
            'monthlyValues'
        ));
    }

    /**
     * Show a single subscription.
     */
    public function show(Subscription $subscription)
    {
        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Edit a subscription.
     */
    public function edit(Subscription $subscription)
    {
        return view('admin.subscriptions.edit', compact('subscription'));
    }

    /**
     * Update a subscription.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'plan'             => 'required|string|max:255',
            'amount'           => 'required|numeric',
            'currency'         => 'required|string|max:3',
            'status'           => 'required|string|in:active,expired,trial,pending',
            'payment_method'   => 'nullable|string|max:255',
            'payment_reference'=> 'nullable|string|max:255',
            'country'          => 'nullable|string|max:255',
            'platform'         => 'nullable|string|max:50',
            'starts_at'        => 'nullable|date',
            'expires_at'       => 'nullable|date|after_or_equal:starts_at',
        ]);

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription updated successfully!');
    }

    /**
     * Delete a subscription.
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription deleted successfully!');
    }
}
