<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Recommendation;
use App\Models\CommunicationLog;
use App\Services\AfricasTalkingService;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class SendRecommendationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recommendationId;

    public function __construct($recommendationId)
    {
        $this->recommendationId = $recommendationId;
    }

    public function handle()
    {
        $rec = Recommendation::find($this->recommendationId);
        if (! $rec) return;

        $user = User::find($rec->user_id);
        if (! $user) return;

        $payload = $rec->payload ?? [];
        $activities = $payload['activities'] ?? [];
        $providers = $payload['providers'] ?? [];

        // Build activity lines with links
        $lines = [];
        foreach ($activities as $idx => $act) {
            $title = $act['title'] ?? ($act['name'] ?? 'Activity');
            $id = $act['id'] ?? null;

            // Prefer activity route, fallback to service route
            if ($id && \Illuminate\Support\Facades\Route::has('activities.show')) {
                $link = route('activities.show', $id);
            } elseif ($id && \Illuminate\Support\Facades\Route::has('services.show')) {
                $link = route('services.show', $id);
            } else {
                $link = url('/');
            }

            $lines[] = ($idx+1) . ") " . $title . " - " . $link;
        }

        $summary = implode(' | ', $lines);

        // In-app / push log
        CommunicationLog::create([
            'user_id' => $rec->user_id,
            'channel' => 'push',
            'type' => 'recommendation',
            'message' => $summary,
            'status' => 'sent',
            'sent_at' => now()
        ]);

        // Send SMS if phone present
        if (! empty($user->phone)) {
            $at = new AfricasTalkingService();
            $smsText = "Hi {$user->name} — here are some activities you might like: " . $summary;
            $res = $at->sendSMS($user->phone, $smsText);

            CommunicationLog::create([
                'user_id' => $rec->user_id,
                'channel' => 'sms',
                'type' => 'recommendation',
                'message' => $smsText,
                'status' => $res['ok'] ? 'sent' : 'failed',
                'sent_at' => now()
            ]);

            // Also log provider-specific entries if providers present
            foreach ($providers as $prov) {
                if (! empty($prov['id'])) {
                    CommunicationLog::create([
                        'user_id' => $rec->user_id,
                        'provider_id' => $prov['id'],
                        'channel' => 'sms',
                        'type' => 'recommendation_provider',
                        'message' => $smsText,
                        'status' => $res['ok'] ? 'sent' : 'failed',
                        'sent_at' => now()
                    ]);
                }
            }
        }

        // WhatsApp (stub) if opted-in
        if (! empty($user->whatsapp_opt_in) && ! empty($user->phone)) {
            $at = new AfricasTalkingService();
            $waRes = $at->sendWhatsApp($user->phone, $summary);

            CommunicationLog::create([
                'user_id' => $rec->user_id,
                'channel' => 'whatsapp',
                'type' => 'recommendation_whatsapp',
                'message' => $summary,
                'status' => $waRes['ok'] ? 'sent' : 'failed',
                'sent_at' => now()
            ]);

            foreach ($providers as $prov) {
                if (! empty($prov['id'])) {
                    CommunicationLog::create([
                        'user_id' => $rec->user_id,
                        'provider_id' => $prov['id'],
                        'channel' => 'whatsapp',
                        'type' => 'recommendation_provider',
                        'message' => $summary,
                        'status' => $waRes['ok'] ? 'sent' : 'failed',
                        'sent_at' => now()
                    ]);
                }
            }
        }
    }
}
