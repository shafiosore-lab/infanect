<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProviderRegisterController extends Controller
{
    public function show()
    {
        return view('auth.provider-register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'phone' => 'nullable|string'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'provider',
            'provider_status' => 'pending',
            'phone' => $data['phone'] ?? null,
        ]);

        // Optionally notify admins about pending provider

        return redirect()->route('login')->with('status','Provider application submitted — pending approval.');
    }
}
