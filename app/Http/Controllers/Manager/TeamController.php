<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    public function index()
    {
        return view('manager.team');
    }
}
