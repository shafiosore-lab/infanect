<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParentingModulesController extends Controller
{
    public function index()
    {
        return view('parenting-modules.index');
    }
}
