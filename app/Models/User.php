<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'provider_type',
        'phone',
        'location',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    /* ==========================
       Relationships
    ========================== */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function providerProfile()
    {
        return $this->hasOne(Provider::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function providedBookings()
    {
        return $this->hasMany(Booking::class, 'provider_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'created_by');
    }

    public function providedActivities()
    {
        return $this->hasMany(Activity::class, 'provider_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'provider_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function moodSubmissions()
    {
        return $this->hasMany(MoodSubmission::class);
    }

    public function engagements()
    {
        return $this->hasMany(Engagement::class);
    }

    /* ==========================
       Scopes
    ========================== */
    public function scopeProviders($query)
    {
        return $query->whereHas('roles', fn($q) => $q->where('slug', 'like', 'provider%'));
    }

    public function scopeClients($query)
    {
        return $query->whereHas('roles', fn($q) => $q->where('slug', 'client'));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /* ==========================
       Helper Methods
    ========================== */
    public function isProvider(): bool
    {
        return $this->hasRole('provider');
    }

    public function isBondingProvider(): bool
    {
        return $this->provider_type === 'provider-bonding';
    }

    public function isProfessionalProvider(): bool
    {
        return $this->provider_type === 'provider-professional';
    }

    /* ==========================
       Progress Helpers
    ========================== */
    public function overallProgress(): float
    {
        $progressRecords = $this->moduleProgress ?? collect();
        return $progressRecords->count() === 0
            ? 0.0
            : round($progressRecords->avg('progress'), 2);
    }

    public function overallProgressBy(string $filter, string $value): float
    {
        $query = $this->moduleProgress()
            ->whereHas('module', fn($q) => $q->where($filter, $value))
            ->get();

        return $query->count() === 0
            ? 0.0
            : round($query->avg('progress'), 2);
    }

    /* ==========================
       Role Checks
    ========================== */
    public function isClient(): bool
    {
        return $this->hasRole('client') || $this->hasRole('user');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('super-admin');
    }

    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    public function isServiceProvider(): bool
    {
        return $this->hasRole('provider-professional');
    }

    public function isActivityProvider(): bool
    {
        return $this->hasRole('provider-bonding');
    }

    public function isUser(): bool
    {
        return $this->hasRole('client');
    }

    /* ==========================
       Access / Permissions
    ========================== */
    public function hasRole(string $role): bool
    {
        return $this->roles->contains('slug', $role)
            || $this->roles->contains('name', $role);
    }

    public function hasPremiumAccess(): bool
    {
        return true;
    }

    public function canListServices(): bool
    {
        return $this->isSuperAdmin() || $this->isServiceProvider() || $this->isUser();
    }

    public function canListActivities(): bool
    {
        return $this->isSuperAdmin() || $this->isActivityProvider() || $this->isUser();
    }

    public function hasPermission(string $perm): bool
    {
        if (method_exists($this, 'hasPermissionTo')) {
            try {
                return $this->hasPermissionTo($perm);
            } catch (\Exception $e) {}
        }

        if ($this->isSuperAdmin()) return true;

        $providerPerms = ['manage services','view bookings','manage clients','view notifications'];
        if (in_array($perm, $providerPerms) && $this->isProvider()) return true;

        $clientPerms = ['submit mood','view recommendations'];
        if (in_array($perm, $clientPerms) && $this->isClient()) return true;

        return false;
    }

    public function providerApproved(): bool
    {
        return $this->isProvider()
            && strtolower($this->provider_status ?? '') === 'approved';
    }

    /* ==========================
       Accessor
    ========================== */
    public function getFullNameWithRoleAttribute(): string
    {
        $role = $this->roles->first()?->name ?? 'No Role';
        return $this->getNameAttribute() . ' (' . $role . ')';
    }

    public function getNameAttribute($value): string
    {
        Log::info('User name attribute', ['user_id' => $this->id, 'value' => $value, 'type' => gettype($value)]);
        if (is_array($value)) {
            return implode(' ', array_map('strval', array_filter($value, 'is_scalar')));
        }
        if (is_object($value)) {
            return method_exists($value, '__toString') ? (string)$value : json_encode($value);
        }
        return (string) ($value ?? '');
    }

    public function getEmailAttribute($value): string
    {
        Log::info('User email attribute', ['user_id' => $this->id, 'value' => $value, 'type' => gettype($value)]);
        if (is_array($value)) {
            return implode('', array_map('strval', array_filter($value, 'is_scalar')));
        }
        if (is_object($value)) {
            return method_exists($value, '__toString') ? (string)$value : json_encode($value);
        }
        return (string) ($value ?? '');
    }
}
