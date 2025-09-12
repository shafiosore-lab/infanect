<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'department',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /* ==========================
     | Relationships
     ========================== */
    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'user_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function moduleProgress(): HasMany
    {
        return $this->hasMany(UserModuleProgress::class, 'user_id');
    }

    public function providerBookings()
    {
        return $this->hasMany(\App\Models\Booking::class, 'provider_id');
    }

    public function providerServices()
    {
        return $this->hasMany(\App\Models\Service::class, 'provider_id');
    }

    public function providerProfile()
    {
        return $this->hasOne(\App\Models\ProviderProfile::class, 'user_id');
    }

    /**
     * Check if the user has a given role.
     * Accepts role id, slug, or name.
     */
    public function hasRole($role): bool
    {
        if (is_null($role)) {
            return false;
        }

        // If role relation is loaded
        if ($this->relationLoaded('role') && $this->role) {
            $r = $this->role;
            if (is_int($role) && $r->id == $role) return true;
            if (is_string($role) && (isset($r->slug) && $r->slug === $role)) return true;
            if (is_string($role) && (isset($r->name) && $r->name === $role)) return true;
        }

        // If a numeric id was provided and role_id exists
        if (is_numeric($role) && isset($this->role_id)) {
            return intval($this->role_id) === intval($role);
        }

        // As a fallback, check against Role table for slug or name
        if (is_string($role)) {
            try {
                $roleModel = \App\Models\Role::where('slug', $role)->orWhere('name', $role)->first();
                if ($roleModel && isset($this->role_id)) {
                    return intval($this->role_id) === intval($roleModel->id);
                }
            } catch (\Throwable $e) {
                // ignore DB errors
            }
        }

        return false;
    }

    /** Check if user has a given role slug */
    public function hasRoleSlug(string $slug): bool
    {
        // If using spatie/permission
        if (method_exists($this, 'hasRole')) {
            try { return $this->hasRole($slug); } catch (\Exception $e) { /* ignore */ }
        }

        return strtolower($this->role ?? '') === strtolower($slug);
    }

    /* ==========================
     | Progress Helpers
     ========================== */
    public function overallProgress(): float
    {
        $progressRecords = $this->moduleProgress;

        if ($progressRecords->count() === 0) {
            return 0.0; // no progress yet
        }

        return round($progressRecords->avg('progress'), 2);
    }

    public function overallProgressBy(string $filter, string $value): float
    {
        $query = $this->moduleProgress()->whereHas('module', function ($q) use ($filter, $value) {
            $q->where($filter, $value);
        })->get();

        if ($query->count() === 0) {
            return 0.0;
        }

        return round($query->avg('progress'), 2);
    }

    /* ==========================
     | Role Checks
     ========================== */
    public function isClient(): bool
    {
        return $this->hasRole('employee') || $this->hasRole('client') || $this->hasRole('user');
    }

    public function isProvider(): bool
    {
        return $this->hasRole('provider') || $this->hasRole('service_provider');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('super-admin') || $this->hasRole('super_admin');
    }

    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin') || $this->hasRole('super_admin') || $this->hasRole('superadmin');
    }

    public function isServiceProvider(): bool
    {
        return $this->hasRole('provider')
            || $this->hasRole('service_provider')
            || $this->hasRole('professional_provider')
            || $this->hasRole('bonding_provider');
    }

    public function isActivityProvider(): bool
    {
        return $this->hasRole('bonding_provider')
            || $this->hasRole('activity_provider')
            || $this->hasRole('provider')
            || $this->hasRole('service_provider');
    }

    public function isUser(): bool
    {
        return $this->hasRole('user')
            || $this->hasRole('client')
            || $this->hasRole('employee')
            || $this->hasRole('member');
    }

    /* ==========================
     | Access / Permissions
     ========================== */
    public function hasPremiumAccess(): bool
    {
        return true; // All users have access to premium content (can be customized later)
    }

    /* ==========================
     | Access Control Methods
     ========================== */
    public function canListServices(): bool
    {
        return $this->isSuperAdmin() || $this->isServiceProvider() || $this->isUser();
    }

    public function canListActivities(): bool
    {
        return $this->isSuperAdmin() || $this->isActivityProvider() || $this->isUser();
    }

    public function canListEmployees(): bool
    {
        return $this->isSuperAdmin() || $this->isServiceProvider() || $this->isActivityProvider();
    }

    public function canManageAllServices(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canManageAllActivities(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canManageOwnServices(): bool
    {
        return $this->isServiceProvider();
    }

    public function canManageOwnActivities(): bool
    {
        return $this->isActivityProvider();
    }

    public function canManageEmployees(): bool
    {
        return $this->isSuperAdmin() || $this->isServiceProvider() || $this->isActivityProvider();
    }

    public function canViewAnalytics(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canApproveServices(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canApproveActivities(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canConfigurePlatform(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canBookServices(): bool
    {
        return $this->isUser();
    }

    public function canRegisterForActivities(): bool
    {
        return $this->isUser();
    }

    public function canLeaveReviews(): bool
    {
        return $this->isUser();
    }

    /** Check permission using spatie or fallback mapping */
    public function hasPermission(string $perm): bool
    {
        if (method_exists($this, 'hasPermissionTo')) {
            try { return $this->hasPermissionTo($perm); } catch (\Exception $e) { /* ignore */ }
        }

        if ($this->isSuperAdmin()) return true;

        $providerPerms = ['manage services','view bookings','manage clients','view notifications'];
        if (in_array($perm, $providerPerms) && $this->isProvider()) return true;

        $clientPerms = ['submit mood','view recommendations'];
        if (in_array($perm, $clientPerms) && $this->isClient()) return true;

        return false;
    }

    /** Helper to check if provider account is approved */
    public function providerApproved(): bool
    {
        return $this->isProvider() && strtolower($this->provider_status ?? '') === 'approved';
    }

    /* ==========================
     | Accessors
     ========================== */
    public function getFullNameWithRoleAttribute(): string
    {
        return $this->name . ' (' . ($this->role->name ?? 'No Role') . ')';
    }
}
