<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Provider;
use Illuminate\Support\Facades\Auth;

class ProviderTransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $provider = Provider::where('user_id', $user->id)->first();

        if (!$provider) {
            return redirect()->route('provider.dashboard')->with('error', 'Provider profile not found.');
        }

        $transactions = Transaction::where('provider_id', $provider->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('provider.transactions.index', compact('transactions', 'provider'));
    }
}
