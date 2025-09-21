<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MentalHealthController extends Controller
{
    /**
     * Display the mental health resources page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('resources.mental-health');
    }
}
