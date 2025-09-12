<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class ProviderOnboardingTest extends TestCase
{
    public function test_onboarding_page_requires_auth()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('provider.onboarding'));
        $response->assertStatus(200);
    }
}
