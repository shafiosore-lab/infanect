<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class FinanceController extends Controller
{
    public function index()
    {
        return view('admin.finance.index');
    }
}
