<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentAdminController extends Controller
{
    public function index()
    {
        $docs = Document::withCount(['chunks as total_chunks_count' => function($q){}, 'chunks as indexed_chunks_count' => function($q){ $q->where('indexed', true); }])->orderBy('created_at','desc')->paginate(20);
        return view('admin.documents.index', compact('docs'));
    }

    public function destroy($id)
    {
        $doc = Document::findOrFail($id);
        // delete file
        if ($doc->filename) Storage::delete($doc->filename);
        $doc->delete();
        return redirect()->route('admin.documents.index')->with('status', 'Document deleted');
    }
}
