<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\AuditLog;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_log_created_on_admin_route()
    {
        $admin = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertStatus(200);

        $this->assertDatabaseCount('audit_logs', 1);
        $this->assertDatabaseHas('audit_logs', ['user_id' => $admin->id]);
    }
}
