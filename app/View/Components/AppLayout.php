<?php

namespace App\View\Components;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AppLayout extends BaseComponent
{
    /**
     * The authenticated user.
     */
    public $user;

    /**
     * The user's role.
     */
    public $role;

    /**
     * Unread notifications count.
     */
    public $notificationCount = 0;

    /**
     * Message count.
     */
    public $messageCount = 0;

    /**
     * Initialize component with dynamic user data.
     */
    public function __construct()
    {
        // Get authenticated user (null if guest)
        $this->user = Auth::user();

        // Determine role if user exists
        if ($this->user) {
            // Check for role from user model based on different potential structures
            if (method_exists($this->user, 'hasRole')) {
                foreach (['super-admin', 'admin', 'provider-professional', 'provider-bonding'] as $role) {
                    if ($this->user->hasRole($role)) {
                        $this->role = $role;
                        break;
                    }
                }
            } else {
                // Try to get from role_id or role property
                $roleMap = [
                    7 => 'admin',
                    8 => 'super-admin',
                    4 => 'provider-professional',
                    5 => 'provider-bonding',
                    3 => 'provider',
                ];

                $this->role = $roleMap[$this->user->role_id ?? 0] ??
                             ($this->user->role ?? 'client');
            }

            // Get notification count if relationship exists
            if (method_exists($this->user, 'unreadNotifications')) {
                $this->notificationCount = $this->user->unreadNotifications()->count();
            }

            // Get message count if relationship exists
            if (method_exists($this->user, 'unreadMessages')) {
                $this->messageCount = $this->user->unreadMessages()->count();
            }
        }
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        // Pass all data to the view
        return view('layouts.app', [
            'user' => $this->user,
            'role' => $this->role,
            'notificationCount' => $this->notificationCount,
            'messageCount' => $this->messageCount,
            'totalBookings' => $this->getTotalBookings(),
            'upcomingBookings' => $this->getUpcomingBookings(),
            'clients' => $this->getClientsData(),
            'recentActivities' => $this->getRecentActivities(),
            'families' => $this->getFamiliesData(),
            'completedModules' => $this->getCompletedModules(),
        ]);
    }

    /**
     * Get total bookings for the user.
     */
    protected function getTotalBookings()
    {
        if (!$this->user) return 0;

        try {
            if (method_exists($this->user, 'bookings')) {
                return $this->user->bookings()->count();
            }
        } catch (\Throwable $e) {
            // Silently fail if relationship doesn't exist
        }

        return 0;
    }

    /**
     * Get upcoming bookings for the user.
     */
    protected function getUpcomingBookings()
    {
        if (!$this->user) return collect([]);

        try {
            if (method_exists($this->user, 'bookings')) {
                return $this->user->bookings()
                    ->where('booking_date', '>=', now()->format('Y-m-d'))
                    ->orderBy('booking_date')
                    ->orderBy('booking_time')
                    ->take(5)
                    ->get();
            }
        } catch (\Throwable $e) {
            // Silently fail if relationship doesn't exist
        }

        return collect([]);
    }

    /**
     * Get clients data for providers.
     */
    protected function getClientsData()
    {
        if (!$this->user || !in_array($this->role, ['provider', 'provider-professional', 'provider-bonding'])) {
            return collect([]);
        }

        try {
            // Get clients for provider
            if (method_exists($this->user, 'clients')) {
                return $this->user->clients()->take(10)->get();
            }
        } catch (\Throwable $e) {
            // Silently fail
        }

        return collect([]);
    }

    /**
     * Get recent activities.
     */
    protected function getRecentActivities()
    {
        if (!$this->user) return collect([]);

        try {
            if (class_exists('App\Models\Activity')) {
                $activityClass = app('App\Models\Activity');

                if (in_array($this->role, ['provider-bonding'])) {
                    // Provider's own activities
                    return $activityClass::where('provider_profile_id', $this->user->id)
                        ->latest()
                        ->take(5)
                        ->get();
                } else {
                    // Activities the user is part of
                    return $activityClass::whereHas('bookings', function($query) {
                        $query->where('user_id', $this->user->id);
                    })
                    ->latest()
                    ->take(5)
                    ->get();
                }
            }
        } catch (\Throwable $e) {
            // Silently fail
        }

        return collect([]);
    }

    /**
     * Get families data for bonding providers.
     */
    protected function getFamiliesData()
    {
        if (!$this->user || $this->role !== 'provider-bonding') {
            return collect([]);
        }

        try {
            // Get unique families who booked activities
            if (class_exists('App\Models\Booking')) {
                return app('App\Models\Booking')::where('provider_id', $this->user->id)
                    ->with('user')
                    ->get()
                    ->pluck('user')
                    ->unique('id');
            }
        } catch (\Throwable $e) {
            // Silently fail
        }

        return collect([]);
    }

    /**
     * Get completed modules for clients.
     */
    protected function getCompletedModules()
    {
        if (!$this->user || $this->role !== 'client') {
            return collect([]);
        }

        try {
            if (method_exists($this->user, 'completedModules')) {
                return $this->user->completedModules;
            }
        } catch (\Throwable $e) {
            // Silently fail
        }

        return collect([]);
    }
}
