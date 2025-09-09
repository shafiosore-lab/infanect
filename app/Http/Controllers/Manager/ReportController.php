<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        return view('manager.reports');
    }
}
