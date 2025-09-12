<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('super-admin.dashboard', [
            'metrics'           => $this->getSystemMetrics(),
            'healthMetrics'     => $this->getHealthMetrics(),
            'geographicData'    => $this->getGeographicData(),
            'monthlyFinancials' => $this->getMonthlyFinancials(),
            'systemAlerts'      => $this->getSystemAlerts(),
        ]);
    }

    private function getSystemMetrics(): object
    {
        return (object)[
            'total_revenue'       => 156780.00,
            'platform_commission' => 15678.00,
            'total_transactions'  => 2340,
            'active_users'        => 1245,
            'total_providers'     => 189,
            'system_uptime'       => 99.8,
            'average_response_time' => 245, // milliseconds
            'database_size'       => 2.4 // GB
        ];
    }

    private function getHealthMetrics(): array
    {
        return [
            'server_performance'   => 92,
            'database_performance' => 95,
            'api_response_time'    => 88,
            'error_rate'           => 2,
            'user_satisfaction'    => 94,
        ];
    }

    private function getGeographicData(): array
    {
        return [
            'Nairobi' => 45,
            'Mombasa' => 25,
            'Kisumu'  => 15,
            'Nakuru'  => 10,
            'Others'  => 5,
        ];
    }

    private function getMonthlyFinancials(): array
    {
        return [
            'August'    => ['revenue' => 12400, 'commission' => 1240, 'transactions' => 156],
            'September' => ['revenue' => 14200, 'commission' => 1420, 'transactions' => 178],
            'October'   => ['revenue' => 13800, 'commission' => 1380, 'transactions' => 165],
            'November'  => ['revenue' => 15600, 'commission' => 1560, 'transactions' => 195],
            'December'  => ['revenue' => 18900, 'commission' => 1890, 'transactions' => 234],
            'January'   => ['revenue' => 8900, 'commission' => 890, 'transactions' => 112],
        ];
    }

    private function getSystemAlerts()
    {
        return collect([
            (object)['level' => 'warning', 'message' => 'Server load approaching 80%', 'time' => '1 hour ago'],
            (object)['level' => 'info',    'message' => 'Database backup completed successfully', 'time' => '3 hours ago'],
            (object)['level' => 'success', 'message' => 'System update deployed successfully', 'time' => '1 day ago'],
        ]);
    }
}
