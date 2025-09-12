<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function insights()
    {
        $insights = $this->generateFinancialInsights();

        return view('financial.insights', compact('insights'));
    }

    public function reports()
    {
        $reports = $this->generateFinancialReports();

        return view('financial.reports', compact('reports'));
    }

    private function generateFinancialInsights()
    {
        return (object)[
            'total_spent_this_month' => 1450.00,
            'total_spent_last_month' => 1200.00,
            'savings_this_month' => 250.00,
            'average_monthly_spending' => 1325.00,
            'spending_categories' => [
                'Activities' => 650.00,
                'Services' => 800.00,
            ],
            'monthly_breakdown' => [
                'January' => 1200.00,
                'February' => 1450.00,
                'March' => 1100.00,
                'April' => 1350.00,
                'May' => 1500.00,
                'June' => 1300.00,
            ],
            'upcoming_expenses' => [
                (object)[
                    'title' => 'Family Safari Adventure',
                    'date' => '2024-02-15',
                    'amount' => 1800.00,
                    'type' => 'activity'
                ],
                (object)[
                    'title' => 'House Cleaning Service',
                    'date' => '2024-02-10',
                    'amount' => 120.00,
                    'type' => 'service'
                ],
            ],
            'budget_recommendations' => [
                'Set aside $200/month for family activities',
                'Consider bulk service bookings for 10% discount',
                'You\'re spending 15% less than similar families',
            ]
        ];
    }

    private function generateFinancialReports()
    {
        return (object)[
            'yearly_summary' => (object)[
                'total_spent' => 15750.00,
                'total_bookings' => 45,
                'average_booking_value' => 350.00,
                'most_expensive_month' => 'December',
                'least_expensive_month' => 'March'
            ],
            'category_breakdown' => [
                'Activities' => (object)[
                    'amount' => 8200.00,
                    'percentage' => 52,
                    'bookings' => 28
                ],
                'Services' => (object)[
                    'amount' => 7550.00,
                    'percentage' => 48,
                    'bookings' => 17
                ],
            ],
            'payment_methods' => [
                'M-Pesa' => 60,
                'Card' => 30,
                'PayPal' => 10
            ]
        ];
    }
}
