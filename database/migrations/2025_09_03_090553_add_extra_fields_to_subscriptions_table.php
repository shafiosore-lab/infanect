<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->boolean('is_trial')->default(false)->after('status');
            $table->integer('trial_days')->nullable()->after('is_trial');
            $table->timestamp('trial_ends_at')->nullable()->after('trial_days');
            $table->boolean('auto_renew')->default(false)->after('trial_ends_at');
            $table->timestamp('cancelled_at')->nullable()->after('expires_at');
            $table->json('features')->nullable()->after('cancelled_at');
            $table->string('billing_cycle')->nullable()->after('features'); // e.g. monthly, yearly
            $table->string('provider')->nullable()->after('billing_cycle'); // e.g. Stripe, PayPal
            $table->boolean('notified_expiry')->default(false)->after('provider');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'is_trial',
                'trial_days',
                'trial_ends_at',
                'auto_renew',
                'cancelled_at',
                'features',
                'billing_cycle',
                'provider',
                'notified_expiry',
            ]);
        });
    }
};
