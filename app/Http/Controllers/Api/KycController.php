<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KycController extends Controller
{
    public function initiate(Request $request)
    {
        // Mock initiation response — integrate with real KYC provider in production
        return response()->json(['status' => 'initiated', 'provider' => 'mock', 'reference' => uniqid('kyc_')]);
    }

    public function callback(Request $request)
    {
        // Handle provider callback; update provider verification status
        // For now just return success
        return response()->json(['status' => 'success']);
    }
}
