<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ProviderMessageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $messages = Message::where('recipient_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('provider.messages.index', compact('messages'));
    }
}
