<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Provider;
use Illuminate\Support\Facades\Auth;

class ProviderFinancialsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $provider = Provider::where('user_id', $user->id)->first();

        if (!$provider) {
            return redirect()->route('provider.dashboard')->with('error', 'Provider profile not found.');
        }

        $transactions = Transaction::where('provider_id', $provider->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalHandled = Transaction::where('provider_id', $provider->id)
            ->where('status', 'completed')
            ->sum('amount');

        return view('provider.financials.index', compact('transactions', 'provider', 'totalHandled'));
    }
}
