<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = User::whereHas('role', fn($q) => $q->where('slug', 'client'))->paginate(10);
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        // implement later
    }

    public function show($id)
    {
        $client = User::findOrFail($id);
        return view('admin.clients.show', compact('client'));
    }

    public function edit($id)
    {
        $client = User::findOrFail($id);
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        // implement later
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.clients.index')->with('success', 'Client deleted.');
    }
}
