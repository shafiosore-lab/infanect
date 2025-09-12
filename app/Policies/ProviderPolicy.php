<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Provider;

class ProviderPolicy
{
    public function view(User $user, Provider $provider)
    {
        if ($user->role === 'super-admin') return true;
        if ($user->role === 'provider' && $provider->user_id === $user->id) return true;
        return false;
    }

    public function update(User $user, Provider $provider)
    {
        return $this->view($user, $provider);
    }
}
