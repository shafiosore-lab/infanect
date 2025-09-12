<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Document;
use App\Models\DocumentChunk;
use App\Jobs\ComputeDocumentEmbeddings;
use App\Services\Ai\EmbeddingService;
use App\Services\Ai\LlmService;
use App\Events\DocumentUploaded;

class AiChatController extends Controller
{
    public function index()
    {
        return view('ai.chat');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf|max:51200',
        ]);

        $file = $request->file('document');
        $path = $file->store('documents');

        $doc = Document::create([
            'filename' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'meta' => null,
        ]);

        $text = null;

        // Try to extract text using Smalot\PdfParser if available
        try {
            if (class_exists('\Smalot\PdfParser\Parser')) {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile(storage_path('app/' . $path));
                $text = $pdf->getText();
            }
        } catch (\Throwable $e) {
            Log::warning('PDF text extraction failed: '.$e->getMessage());
            $text = null;
        }

        // Fallback: small placeholder text
        if (empty($text)) {
            $text = 'Uploaded document: ' . $file->getClientOriginalName();
        }

        // Split text into chunks of ~800 characters (safe chunking)
        $chunkSize = 800;
        $clean = preg_replace('/\s+/', ' ', trim($text));
        $parts = str_split($clean, $chunkSize);

        foreach ($parts as $i => $part) {
            DocumentChunk::create([
                'document_id' => $doc->id,
                'chunk_index' => $i,
                'text' => $part,
            ]);
        }

        // Dispatch job to compute embeddings (async) if queue configured
        try {
            ComputeDocumentEmbeddings::dispatch($doc->id);
        } catch (\Throwable $e) {
            // If dispatch fails, run synchronously
            try { (new ComputeDocumentEmbeddings($doc->id))->handle(new EmbeddingService()); } catch (\Throwable $ex) { Log::error('Embeddings dispatch failed: '.$ex->getMessage()); }
        }

        // Dispatch real-time event for admin listeners
        try {
            event(new DocumentUploaded($doc));
        } catch (\Throwable $e) {
            Log::warning('Event dispatch failed: '.$e->getMessage());
        }

        return back()->with('status', 'Document uploaded and processed.');
    }

    public function documents(Request $request)
    {
        $docs = Document::withCount(['chunks as total_chunks_count' => function($q){}, 'chunks as indexed_chunks_count' => function($q){ $q->where('indexed', true); }])->orderBy('created_at','desc')->get();

        $payload = $docs->map(function($d){
            return [
                'id' => $d->id,
                'original_name' => $d->original_name,
                'filename' => $d->filename,
                'total_chunks' => $d->total_chunks_count ?? 0,
                'indexed_chunks' => $d->indexed_chunks_count ?? 0,
                'created_at' => $d->created_at->toDateTimeString(),
            ];
        });

        return response()->json(['documents' => $payload]);
    }

    public function message(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        $q = $request->input('message');

        $embedSvc = new EmbeddingService();
        $qEmbedding = $embedSvc->embedText($q);

        $context = '';

        if (empty($qEmbedding)) {
            // Fallback to simple LIKE search when embeddings unavailable
            $terms = preg_split('/\s+/', $q);
            $query = DocumentChunk::query();
            foreach ($terms as $t) {
                $query->orWhere('text', 'like', "%{$t}%");
            }
            $chunks = $query->limit(5)->get();
            $context = $chunks->pluck('text')->implode('\n---\n');
        } else {
            // Semantic search: fetch indexed chunks and compute cosine similarity in PHP
            $candidates = DocumentChunk::whereNotNull('embedding')->where('indexed', true)->limit(1000)->get();
            $scores = [];
            foreach ($candidates as $c) {
                $emb = $c->embedding ?? null;
                if (is_array($emb) && count($emb) > 0) {
                    $score = $this->cosineSimilarity($qEmbedding, $emb);
                    $scores[] = ['chunk' => $c, 'score' => $score];
                }
            }

            usort($scores, fn($a,$b) => $b['score'] <=> $a['score']);
            $top = array_slice($scores, 0, 5);

            $context = collect($top)->pluck('chunk')->map(fn($c) => $c->text . "\n(Source: " . ($c->document->original_name ?? $c->document->filename) . ")")->implode('\n---\n');
        }

        // Use LLM to generate final answer from query + context
        $llm = new LlmService();
        $answer = $llm->generateAnswer($q, $context);

        $reply = $answer ?? "I found the following relevant excerpts:\n" . $context . "\n\nYou asked: {$q}\nReply: (fallback)";

        return response()->json(['reply' => $reply]);
    }

    protected function cosineSimilarity(array $a, array $b): float
    {
        $dot = 0.0; $na = 0.0; $nb = 0.0;
        $len = min(count($a), count($b));
        for ($i=0;$i<$len;$i++) {
            $dot += ($a[$i] * $b[$i]);
            $na += ($a[$i] * $a[$i]);
            $nb += ($b[$i] * $b[$i]);
        }
        if ($na == 0 || $nb == 0) return 0.0;
        return $dot / (sqrt($na) * sqrt($nb));
    }
}
