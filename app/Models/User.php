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
     * The attributes that should be cast.
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
        return $this->belongsTo(Role::class);
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
    public function hasRole($role): bool
    {
        return $this->role && $this->role->slug === $role;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    public function isServiceProvider(): bool
    {
        return $this->hasRole('service-provider');
    }

    public function isActivityProvider(): bool
    {
        return $this->hasRole('activity-provider');
    }

    public function isEmployee(): bool
    {
        return $this->hasRole('employee');
    }

    public function isUser(): bool
    {
        return $this->hasRole('user');
    }

    // Legacy methods for backward compatibility
    public function isAdmin(): bool
    {
        return $this->isSuperAdmin();
    }

    public function isManager(): bool
    {
        return $this->isSuperAdmin(); // Manager role now maps to Super Admin
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

    /* ==========================
     | Accessors
     ========================== */
    public function getFullNameWithRoleAttribute(): string
    {
        return $this->name . ' (' . ($this->role->name ?? 'No Role') . ')';
    }
}
