<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentAdminController extends Controller
{
    /**
     * Display a list of uploaded documents with chunk stats.
     */
    public function index()
    {
        $docs = Document::withCount([
            'chunks as total_chunks_count',
            'chunks as indexed_chunks_count' => function ($q) {
                $q->where('indexed', true);
            }
        ])
        ->latest()
        ->paginate(20);

        return view('admin.documents.index', compact('docs'));
    }

    /**
     * Show upload form.
     */
    public function create()
    {
        return view('admin.documents.create');
    }

    /**
     * Store a new document.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,txt|max:20480', // 20MB max
        ]);

        $file = $request->file('file');

        // Store in default local disk under "documents/"
        $path = $file->store('documents', 'local');

        // Create DB record
        $doc = Document::create([
            'name' => $file->getClientOriginalName(),
            'filename' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return redirect()
            ->route('admin.documents.index')
            ->with('status', 'Document uploaded successfully.');
    }

    /**
     * Remove a document and its stored file.
     */
    public function destroy($id)
    {
        $doc = Document::findOrFail($id);

        if ($doc->filename) {
            try {
                Storage::disk('local')->delete($doc->filename);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $doc->delete();

        return redirect()
            ->route('admin.documents.index')
            ->with('status', 'Document deleted successfully.');
    }
}
