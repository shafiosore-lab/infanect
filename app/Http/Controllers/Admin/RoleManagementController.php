<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RoleManagementController extends Controller
{
    public function index()
    {
        $users = User::paginate(20);
        $roles = ['super-admin','provider','provider-professional','provider-bonding','client'];
        return view('admin.roles.index', compact('users','roles'));
    }

    public function edit(User $user)
    {
        $roles = ['super-admin','provider','provider-professional','provider-bonding','client'];
        return view('admin.roles.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate(['role' => 'required|string']);
        $user->role = $data['role'];
        $user->save();
        return redirect()->route('admin.roles.management.index')->with('status', 'Role updated');
    }
}
