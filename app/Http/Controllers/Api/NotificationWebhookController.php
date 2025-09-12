<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Validate webhook signature in production
        // For now log and return 200
        \Log::info('Notification webhook received', $request->all());
        return response()->json(['status' => 'ok']);
    }
}
