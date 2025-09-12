<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bookings')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('tenant_id');
            }
            if (!Schema::hasColumn('bookings', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('bookings', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_email');
            }
            if (!Schema::hasColumn('bookings', 'country')) {
                $table->string('country')->nullable()->after('customer_phone');
            }
            if (!Schema::hasColumn('bookings', 'country_code')) {
                $table->string('country_code', 10)->nullable()->after('country');
            }
            if (!Schema::hasColumn('bookings', 'booking_date')) {
                $table->dateTime('booking_date')->nullable()->after('country_code');
            }
            if (!Schema::hasColumn('bookings', 'scheduled_at')) {
                $table->dateTime('scheduled_at')->nullable()->after('booking_date');
            }
            if (!Schema::hasColumn('bookings', 'status')) {
                $table->string('status')->nullable()->after('scheduled_at');
            }
            if (!Schema::hasColumn('bookings', 'location')) {
                $table->string('location')->nullable()->after('status');
            }
            if (!Schema::hasColumn('bookings', 'timezone')) {
                $table->string('timezone')->nullable()->after('location');
            }
            if (!Schema::hasColumn('bookings', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('timezone');
            }
            if (!Schema::hasColumn('bookings', 'currency_code')) {
                $table->string('currency_code', 10)->nullable()->after('price');
            }
            if (!Schema::hasColumn('bookings', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable()->after('currency_code');
            }
            if (!Schema::hasColumn('bookings', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('bookings', 'participants')) {
                $table->integer('participants')->nullable()->after('amount_paid');
            }
            if (!Schema::hasColumn('bookings', 'discount')) {
                $table->decimal('discount', 10, 2)->nullable()->after('participants');
            }
            if (!Schema::hasColumn('bookings', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('discount');
            }
            if (!Schema::hasColumn('bookings', 'payment_ref')) {
                $table->string('payment_ref')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('bookings', 'rating')) {
                $table->decimal('rating', 5, 2)->nullable()->after('payment_ref');
            }
            if (!Schema::hasColumn('bookings', 'is_returning')) {
                $table->boolean('is_returning')->default(false)->after('rating');
            }
            if (!Schema::hasColumn('bookings', 'reference_code')) {
                $table->string('reference_code')->nullable()->after('is_returning')->index();
            }
            if (!Schema::hasColumn('bookings', 'notes')) {
                $table->text('notes')->nullable()->after('reference_code');
            }
            if (!Schema::hasColumn('bookings', 'platform')) {
                $table->string('platform')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('bookings', 'metadata')) {
                $table->json('metadata')->nullable()->after('platform');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('bookings')) return;

        Schema::table('bookings', function (Blueprint $table) {
            $cols = [
                'customer_name','customer_email','customer_phone','country','country_code','booking_date','scheduled_at','status','location','timezone','price','currency_code','amount','amount_paid','participants','discount','payment_method','payment_ref','rating','is_returning','reference_code','notes','platform','metadata'
            ];
            foreach ($cols as $c) {
                if (Schema::hasColumn('bookings', $c)) {
                    try { $table->dropColumn($c); } catch (\Throwable $e) {}
                }
            }
        });
    }
};
