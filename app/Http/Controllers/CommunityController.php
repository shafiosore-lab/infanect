<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityPost;

class CommunityController extends Controller
{
    public function index()
    {
        $posts = CommunityPost::latest()->paginate(15);
        return view('community.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string|max:2000',
            'group' => 'nullable|string'
        ]);

        CommunityPost::create(array_merge($data, ['user_id' => $request->user()->id, 'status' => 'pending']));

        return redirect()->route('community.index')->with('status', 'Post submitted — pending moderation');
    }
}
