<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Provider;
use App\Models\User;
use App\Notifications\KYCSubmittedNotification;
use App\Notifications\KYCApprovedNotification;

class ProcessKYCDocuments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $providerId;

    public function __construct($providerId)
    {
        $this->providerId = $providerId;
    }

    public function handle()
    {
        $provider = Provider::find($this->providerId);

        if (!$provider) {
            return;
        }

        // Send confirmation email to provider
        $provider->user->notify(new KYCSubmittedNotification($provider));

        // Notify admin users about new provider registration
        $admins = User::whereIn('role_id', ['admin', 'super-admin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new KYCSubmittedNotification($provider));
        }

        // Auto-approve certain provider types for testing
        if (config('app.env') === 'local' || in_array($provider->provider_type, ['provider-bonding'])) {
            $this->autoApprove($provider);
        }
    }

    private function autoApprove($provider)
    {
        $provider->update([
            'status' => 'approved',
            'kyc_status' => 'approved',
            'approved_at' => now()
        ]);

        // Update user role to reflect approval
        $provider->user->update([
            'provider_data' => json_encode([
                'provider_type' => $provider->provider_type,
                'registration_stage' => 'approved'
            ])
        ]);

        $provider->user->notify(new KYCApprovedNotification($provider));
    }
}
