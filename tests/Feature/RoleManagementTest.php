<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_update_user_role()
    {
        $admin = User::factory()->create(['role' => 'super-admin']);
        $user = User::factory()->create(['role' => 'client']);

        $this->actingAs($admin)
            ->put(route('admin.roles.management.update', $user), ['role' => 'provider'])
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'provider']);
    }
}
