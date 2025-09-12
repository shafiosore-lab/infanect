<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SavedLessonController extends Controller
{
    protected $maxPerType = 2;

    public function save(Request $request)
    {
        $data = $request->only(['type','external_id','title','url']);
        if (!in_array($data['type'], ['audio','video'])) return redirect()->back();

        $key = 'saved_lessons.' . $data['type'];
        $list = $request->session()->get($key, []);

        // prevent duplicates
        foreach ($list as $item) { if (($item['external_id'] ?? '') === ($data['external_id'] ?? '')) return redirect()->back(); }

        // push and trim
        $list[] = $data;
        while (count($list) > $this->maxPerType) array_shift($list);
        $request->session()->put($key, $list);

        if ($request->wantsJson()) return response()->json(['status'=>'ok','saved'=>$list]);
        return redirect()->back()->with('status','Saved');
    }

    public function delete(Request $request)
    {
        $type = $request->input('type');
        $external = $request->input('external_id');
        $key = 'saved_lessons.' . $type;
        $list = $request->session()->get($key, []);
        $list = array_filter($list, function($i) use($external){ return ($i['external_id'] ?? '') !== $external; });
        $request->session()->put($key, array_values($list));
        if ($request->wantsJson()) return response()->json(['status'=>'ok']);
        return redirect()->back();
    }

    public function list(Request $request)
    {
        $audio = $request->session()->get('saved_lessons.audio', []);
        $video = $request->session()->get('saved_lessons.video', []);
        return response()->json(['audio'=>$audio,'video'=>$video]);
    }
}
