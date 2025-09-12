<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class DashboardMetricsService
{
    protected $cache = [];

    public function getMetrics(User $user): array
    {
        return [
            'total_clients'            => $this->getTotalClients($user),
            'active_sessions'          => $this->getActiveSessions($user),
            'monthly_revenue'          => $this->getMonthlyRevenue($user),
            'client_satisfaction'      => $this->getClientSatisfactionScore($user),
            'pending_appointments'     => $this->getPendingAppointments($user),
            'completed_sessions_today' => $this->getTodayCompletedSessions($user),

            // Financial
            'quarterly_revenue'        => $this->getQuarterlyRevenue($user),
            'yearly_revenue'           => $this->getYearlyRevenue($user),
            'outstanding_payments'     => $this->getOutstandingPayments($user),
            'average_session_rate'     => $this->getAverageSessionRate($user),

            // Service Performance
            'most_popular_service'     => $this->getMostPopularService($user),
            'service_conversion_rate'  => $this->getServiceConversionRate($user),
            'cancellation_rate'        => $this->getCancellationRate($user),
            'no_show_rate'             => $this->getNoShowRate($user),

            // Engagement
            'new_clients_this_month'   => $this->getNewClientsThisMonth($user),
            'returning_clients_rate'   => $this->getReturningClientsRate($user),
            'client_lifetime_value'    => $this->getClientLifetimeValue($user),
            'average_sessions_per_client' => $this->getAverageSessionsPerClient($user),
        ];
    }

    private function getTotalClients(User $user): int
    {
        try {
            return DB::table('bookings')
                ->where('provider_id', $user->id)
                ->distinct('user_id')
                ->count('user_id') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getActiveSessions(User $user): int
    {
        try {
            return DB::table('bookings')
                ->where('provider_id', $user->id)
                ->where('status', 'confirmed')
                ->whereDate('booking_date', '>=', now())
                ->count() ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getMonthlyRevenue(User $user): float
    {
        try {
            return DB::table('bookings')
                ->where('provider_id', $user->id)
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getClientSatisfactionScore(User $user): float
    {
        try {
            return round(DB::table('reviews')
                ->where('provider_id', $user->id)
                ->avg('rating') ?? 0, 1);
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getPendingAppointments(User $user): int
    {
        try {
            return DB::table('bookings')
                ->where('provider_id', $user->id)
                ->where('status', 'pending')
                ->count() ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getTodayCompletedSessions(User $user): int
    {
        try {
            return DB::table('bookings')
                ->where('provider_id', $user->id)
                ->where('status', 'completed')
                ->whereDate('booking_date', now())
                ->count() ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getQuarterlyRevenue(User $user): float
    {
        try {
            $currentQuarter = ceil(now()->month / 3);
            $quarterStart   = now()->startOfYear()->addMonths(($currentQuarter - 1) * 3);
            $quarterEnd     = $quarterStart->copy()->addMonths(2)->endOfMonth();

            return DB::table('bookings')
                ->where('provider_id', $user->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$quarterStart, $quarterEnd])
                ->sum('amount') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getYearlyRevenue(User $user): float
    {
        try {
            return DB::table('bookings')
                ->where('provider_id', $user->id)
                ->where('status', 'completed')
                ->whereYear('created_at', now()->year)
                ->sum('amount') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getOutstandingPayments(User $user): float
    {
        try {
            return DB::table('bookings')
                ->where('provider_id', $user->id)
                ->whereIn('status', ['confirmed', 'pending'])
                ->sum('amount') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getAverageSessionRate(User $user): float
    {
        try {
            return round(DB::table('bookings')
                ->where('provider_id', $user->id)
                ->where('status', 'completed')
                ->avg('amount') ?? 0, 2);
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getMostPopularService(User $user): string
    {
        try {
            $service = DB::table('bookings')
                ->leftJoin('services', 'bookings.service_id', '=', 'services.id')
                ->where('bookings.provider_id', $user->id)
                ->select(DB::raw('COALESCE(services.name, services.title, "Service") as name'), DB::raw('COUNT(*) as booking_count'))
                ->groupBy('services.id', 'services.name', 'services.title')
                ->orderBy('booking_count', 'desc')
                ->first();

            return $service->name ?? 'N/A';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function getServiceConversionRate(User $user): float
    {
        try {
            $totalInquiries   = DB::table('bookings')->where('provider_id', $user->id)->count();
            $confirmedBookings = DB::table('bookings')
                ->where('provider_id', $user->id)
                ->whereIn('status', ['confirmed', 'completed'])
                ->count();

            return $totalInquiries > 0 ? round(($confirmedBookings / $totalInquiries) * 100, 1) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getCancellationRate(User $user): float
    {
        try {
            $total   = DB::table('bookings')->where('provider_id', $user->id)->count();
            $cancel  = DB::table('bookings')->where('provider_id', $user->id)->where('status', 'cancelled')->count();

            return $total > 0 ? round(($cancel / $total) * 100, 1) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getNoShowRate(User $user): float
    {
        try {
            $total = DB::table('bookings')
                ->where('provider_id', $user->id)
                ->whereIn('status', ['confirmed', 'completed', 'no-show'])
                ->count();

            $noShow = DB::table('bookings')
                ->where('provider_id', $user->id)
                ->where('status', 'no-show')
                ->count();

            return $total > 0 ? round(($noShow / $total) * 100, 1) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getNewClientsThisMonth(User $user): int
    {
        try {
            return DB::table('bookings')
                ->where('provider_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->distinct('user_id')
                ->count('user_id') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getReturningClientsRate(User $user): float
    {
        try {
            $totalClients     = $this->getTotalClients($user);
            $returningClients = DB::table('bookings')
                ->where('provider_id', $user->id)
                ->select('user_id')
                ->groupBy('user_id')
                ->havingRaw('COUNT(*) > 1')
                ->count();

            return $totalClients > 0 ? round(($returningClients / $totalClients) * 100, 1) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getClientLifetimeValue(User $user): float
    {
        try {
            $revenue = DB::table('bookings')
                ->where('provider_id', $user->id)
                ->where('status', 'completed')
                ->sum('amount') ?? 0;

            $clients = $this->getTotalClients($user);

            return $clients > 0 ? round($revenue / $clients, 2) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getAverageSessionsPerClient(User $user): float
    {
        try {
            $bookings = DB::table('bookings')->where('provider_id', $user->id)->count();
            $clients  = $this->getTotalClients($user);

            return $clients > 0 ? round($bookings / $clients, 1) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
