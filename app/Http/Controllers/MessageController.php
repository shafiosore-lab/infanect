<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationTemplate;
use App\Models\CommunicationLog;
use App\Jobs\SendRecommendationJob;

class MessageController extends Controller
{
    public function sendTransactional($type, $userId, $data = [])
    {
        // Lookup template -> dispatch send job (implementation placeholder)
        $template = NotificationTemplate::where('type', $type)->where('channel', $data['channel'] ?? 'email')->first();
        // Log and dispatch job
        CommunicationLog::create([
            'user_id' => $userId,
            'provider_id' => $data['provider_id'] ?? null,
            'channel' => $data['channel'] ?? 'email',
            'type' => $type,
            'message' => $template->content ?? ($data['message'] ?? ''),
            'status' => 'queued'
        ]);

        // Dispatch send job (placeholder)
        SendRecommendationJob::dispatch($userId, $data);

        return response()->json(['status' => 'queued']);
    }

    public function sendBulk(Request $request)
    {
        $payload = $request->validate([
            'template_id' => 'required|exists:notification_templates,id',
            'channel' => 'required|in:email,sms',
            'filters' => 'nullable|array'
        ]);

        // TODO: select users per filters and dispatch jobs in batches
        return response()->json(['status' => 'queued']);
    }

    public function sendRandom(Request $request)
    {
        $payload = $request->validate(['type' => 'required|string','channel' => 'required|in:email,sms']);
        $template = NotificationTemplate::where('type', $payload['type'])->inRandomOrder()->first();
        // TODO: send to target audience
        return response()->json(['template' => $template]);
    }

    public function logs(Request $request)
    {
        $logs = CommunicationLog::with('user')->orderBy('created_at', 'desc')->paginate(25);
        return view('admin.messages.logs', compact('logs'));
    }

    public function index()
    {
        $messages = $this->generateMessages();

        return view('messages.index', compact('messages'));
    }

    public function show($id)
    {
        $message = $this->getMessageById($id);

        if (!$message) {
            abort(404);
        }

        return view('messages.show', compact('message'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // In a real application, you would save to database

        return redirect()->route('messages.index')
                        ->with('success', 'Message sent successfully!');
    }

    private function generateMessages()
    {
        return collect([
            (object)[
                'id' => 1,
                'sender' => 'Infanect Support',
                'subject' => 'Welcome to Infanect!',
                'preview' => 'Thank you for joining our family-focused platform...',
                'content' => 'Welcome to Infanect! We\'re excited to have you as part of our community. Explore our amazing activities and services designed specifically for families.',
                'is_read' => true,
                'created_at' => '2024-01-20 10:30:00',
                'type' => 'system'
            ],
            (object)[
                'id' => 2,
                'sender' => 'Activity Coordinator',
                'subject' => 'Booking Confirmation - Safari Adventure',
                'preview' => 'Your booking for Family Safari Adventure has been confirmed...',
                'content' => 'Your booking for Family Safari Adventure on February 15th has been confirmed. Please arrive 30 minutes early at the meeting point.',
                'is_read' => false,
                'created_at' => '2024-01-18 14:15:00',
                'type' => 'booking'
            ],
            (object)[
                'id' => 3,
                'sender' => 'Community Manager',
                'subject' => 'New Family Activity Available',
                'preview' => 'Check out our latest activity: Art & Craft Workshop...',
                'content' => 'We\'ve just added a new Art & Craft Workshop perfect for families with children aged 5-12. Book now for early bird pricing!',
                'is_read' => false,
                'created_at' => '2024-01-15 09:45:00',
                'type' => 'promotion'
            ],
        ]);
    }

    private function getMessageById($id)
    {
        return $this->generateMessages()->firstWhere('id', $id);
    }
}
