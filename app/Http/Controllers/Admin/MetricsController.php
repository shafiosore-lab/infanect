<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Provider;
use App\Models\Transaction;

class MetricsController extends Controller
{
    public function index()
    {
        // Mock time series for the last 7 days
        $labels = [];
        $users = [];
        $revenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $labels[] = now()->subDays($i)->format('M d');
            $users[] = rand(5, 40);
            $revenue[] = rand(100, 5000);
        }

        $doughnut = [
            'labels' => ['Wellness','Education','Training','Therapy','Bonding'],
            'data' => [rand(10,40), rand(10,30), rand(5,20), rand(2,10), rand(1,5)]
        ];

        return response()->json([ 'labels' => $labels, 'users' => $users, 'revenue' => $revenue, 'doughnut' => $doughnut ]);
    }
}
