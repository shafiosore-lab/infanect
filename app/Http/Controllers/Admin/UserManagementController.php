<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(20);
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $roleId = $request->input('role_id');
        $role = Role::findOrFail($roleId);
        $user->role_id = $role->id;
        $user->save();
        return redirect()->route('admin.users.index')->with('status', 'Role updated');
    }
}
