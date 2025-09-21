<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Document;
use App\Models\DocumentChunk;
use App\Models\User;
use App\Jobs\ComputeDocumentEmbeddings;
use App\Services\Ai\EmbeddingService;
use App\Services\Ai\LlmService;
use App\Events\DocumentUploaded;

class AiChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get dashboard data for the chat interface
        $dashboardData = $this->getChatDashboardData($user);

        return view('ai.mental-health-assistant', compact('dashboardData'));
    }

    public function upload(Request $request)
    {
        // Only allow verified providers to upload research
        if (!$this->isVerifiedProvider(auth()->user())) {
            return response()->json(['error' => 'Only verified mental health providers can upload research documents.'], 403);
        }

        $request->validate([
            'document' => 'required|file|mimes:pdf|max:51200',
            'title' => 'required|string|max:500',
            'authors' => 'required|string|max:500',
            'publication_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'journal' => 'nullable|string|max:300',
            'doi' => 'nullable|string|max:200',
            'document_type' => 'required|in:systematic_review,rct,meta_analysis,clinical_guideline,case_study,theoretical_paper',
            'topics' => 'required|array|min:1',
            'topics.*' => 'string|in:anxiety,depression,trauma,addiction,family_therapy,child_psychology,adolescent_mental_health,cognitive_behavioral_therapy,mindfulness,parent_child_bonding,crisis_intervention,grief_counseling,bipolar_disorder,eating_disorders,adhd,autism_spectrum,substance_abuse,ptsd,ocd,personality_disorders',
            'summary' => 'nullable|string|max:1000',
        ]);

        $file = $request->file('document');
        $path = $file->store('research-documents');

        $doc = Document::create([
            'filename' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'status' => 'pending_review',
            'meta' => [
                'type' => 'research_document',
                'title' => $request->title,
                'authors' => $request->authors,
                'publication_year' => $request->publication_year,
                'journal' => $request->journal,
                'doi' => $request->doi,
                'document_type' => $request->document_type,
                'topics' => $request->topics,
                'summary' => $request->summary,
                'uploaded_by' => auth()->id(),
                'uploaded_by_name' => auth()->user()->name,
                'verified' => false,
                'peer_reviewed' => !empty($request->doi),
                'upload_timestamp' => now()->toISOString(),
            ],
        ]);

        // Extract text from PDF
        $text = $this->extractPdfText($path);

        if (empty($text)) {
            $text = "Research Document: {$request->title} by {$request->authors} ({$request->publication_year})\n";
            $text .= "Topics: " . implode(', ', $request->topics) . "\n";
            $text .= "Summary: {$request->summary}";
        }

        // Create smart chunks with research context
        $this->createResearchChunks($doc, $text);

        // Process embeddings
        try {
            ComputeDocumentEmbeddings::dispatch($doc->id);
        } catch (\Throwable $e) {
            try {
                (new ComputeDocumentEmbeddings($doc->id))->handle(new EmbeddingService());
            } catch (\Throwable $ex) {
                Log::error('Research document embeddings failed: ' . $ex->getMessage());
            }
        }

        event(new DocumentUploaded($doc));

        return response()->json([
            'success' => true,
            'message' => 'Research document uploaded successfully and is pending review.',
            'document_id' => $doc->id
        ]);
    }

    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'conversation_id' => 'nullable|string'
        ]);

        $query = $request->input('message');
        $conversationId = $request->input('conversation_id', uniqid());

        // Get research context using semantic search
        $context = $this->getResearchContext($query);

        // Generate mental health research-grounded response
        $response = $this->generateResearchResponse($query, $context);

        // Log the interaction for analytics
        $this->logChatInteraction($query, $response, $conversationId);

        return response()->json([
            'reply' => $response['answer'],
            'sources' => $response['sources'],
            'conversation_id' => $conversationId,
            'disclaimer' => 'This information is for educational purposes only and is not a substitute for professional mental health advice, diagnosis, or treatment.',
        ]);
    }

    public function documents(Request $request)
    {
        $user = auth()->user();

        $query = Document::with(['chunks' => function($q) {
            $q->select('document_id', \DB::raw('COUNT(*) as total_chunks'), \DB::raw('SUM(CASE WHEN indexed = 1 THEN 1 ELSE 0 END) as indexed_chunks'));
        }])
        ->where('meta->type', 'research_document');

        // Filter by user role
        if ($user->role !== 'admin') {
            if ($user->role === 'provider') {
                $query->where('meta->uploaded_by', $user->id);
            } else {
                $query->where('meta->verified', true)->where('status', 'approved');
            }
        }

        $documents = $query->orderBy('created_at', 'desc')->get();

        $payload = $documents->map(function($doc) {
            $meta = $doc->meta ?? [];
            return [
                'id' => $doc->id,
                'title' => $meta['title'] ?? $doc->original_name,
                'authors' => $meta['authors'] ?? 'Unknown',
                'journal' => $meta['journal'] ?? 'N/A',
                'year' => $meta['publication_year'] ?? 'N/A',
                'type' => $meta['document_type'] ?? 'unknown',
                'topics' => $meta['topics'] ?? [],
                'doi' => $meta['doi'] ?? null,
                'verified' => $meta['verified'] ?? false,
                'peer_reviewed' => $meta['peer_reviewed'] ?? false,
                'total_chunks' => $doc->chunks->sum('total_chunks') ?? 0,
                'indexed_chunks' => $doc->chunks->sum('indexed_chunks') ?? 0,
                'status' => $doc->status,
                'uploaded_by' => $meta['uploaded_by_name'] ?? 'Unknown',
                'created_at' => $doc->created_at->toDateTimeString(),
            };
        });

        return response()->json(['documents' => $payload]);
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

    private function isVerifiedProvider(User $user): bool
    {
        return $user->role === 'provider' &&
               $user->providerProfile &&
               $user->providerProfile->kyc_status === 'approved';
    }

    private function extractPdfText($path): ?string
    {
        try {
            if (class_exists('\Smalot\PdfParser\Parser')) {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile(storage_path('app/' . $path));
                return $pdf->getText();
            }
        } catch (\Throwable $e) {
            Log::warning('PDF text extraction failed: ' . $e->getMessage());
        }
        return null;
    }

    private function createResearchChunks(Document $doc, string $text): void
    {
        $meta = $doc->meta ?? [];
        $chunkSize = 1000; // Larger chunks for research papers
        $overlap = 200; // Overlap between chunks for context preservation

        $clean = preg_replace('/\s+/', ' ', trim($text));
        $textLength = strlen($clean);

        $chunks = [];
        $position = 0;
        $index = 0;

        while ($position < $textLength) {
            $chunkEnd = min($position + $chunkSize, $textLength);
            $chunk = substr($clean, $position, $chunkEnd - $position);

            // Add research metadata to chunk
            $enrichedChunk = "RESEARCH: {$meta['title']} ({$meta['publication_year']})\n";
            $enrichedChunk .= "AUTHORS: {$meta['authors']}\n";
            $enrichedChunk .= "TOPICS: " . implode(', ', $meta['topics'] ?? []) . "\n\n";
            $enrichedChunk .= $chunk;

            DocumentChunk::create([
                'document_id' => $doc->id,
                'chunk_index' => $index,
                'text' => $enrichedChunk,
                'meta' => [
                    'research_context' => true,
                    'topics' => $meta['topics'] ?? [],
                    'document_type' => $meta['document_type'] ?? 'unknown',
                    'peer_reviewed' => $meta['peer_reviewed'] ?? false,
                ]
            ]);

            $position += $chunkSize - $overlap;
            $index++;
        }
    }

    private function getResearchContext(string $query): array
    {
        $embedSvc = new EmbeddingService();
        $qEmbedding = $embedSvc->embedText($query);

        if (empty($qEmbedding)) {
            // Fallback to keyword search
            $terms = preg_split('/\s+/', $query);
            $chunks = DocumentChunk::whereHas('document', function($q) {
                $q->where('meta->verified', true)->where('status', 'approved');
            });

            foreach ($terms as $term) {
                $chunks->orWhere('text', 'like', "%{$term}%");
            }

            $results = $chunks->limit(5)->get();
        } else {
            // Semantic search on approved research
            $candidates = DocumentChunk::whereNotNull('embedding')
                ->where('indexed', true)
                ->whereHas('document', function($q) {
                    $q->where('meta->verified', true)->where('status', 'approved');
                })
                ->limit(1000)
                ->get();

            $scores = [];
            foreach ($candidates as $chunk) {
                $embedding = $chunk->embedding ?? null;
                if (is_array($embedding) && count($embedding) > 0) {
                    $score = $this->cosineSimilarity($qEmbedding, $embedding);
                    $scores[] = ['chunk' => $chunk, 'score' => $score];
                }
            }

            usort($scores, fn($a, $b) => $b['score'] <=> $a['score']);
            $results = collect(array_slice($scores, 0, 5))->pluck('chunk');
        }

        return $results->map(function($chunk) {
            $doc = $chunk->document;
            $meta = $doc->meta ?? [];

            return [
                'text' => $chunk->text,
                'source' => [
                    'title' => $meta['title'] ?? $doc->original_name,
                    'authors' => $meta['authors'] ?? 'Unknown',
                    'year' => $meta['publication_year'] ?? 'N/A',
                    'journal' => $meta['journal'] ?? null,
                    'doi' => $meta['doi'] ?? null,
                    'document_type' => $meta['document_type'] ?? 'unknown',
                    'topics' => $meta['topics'] ?? [],
                ]
            ];
        })->toArray();
    }

    private function generateResearchResponse(string $query, array $context): array
    {
        $contextText = collect($context)->map(function($item) {
            $source = $item['source'];
            return $item['text'] . "\n\n(Source: {$source['title']} by {$source['authors']} ({$source['year']})" .
                   ($source['doi'] ? " DOI: {$source['doi']}" : "") . ")";
        })->implode("\n---\n");

        $systemPrompt = "You are a mental health research assistant. Your role is to provide evidence-based information from peer-reviewed research.

Guidelines:
- Always ground your answers in the provided research context
- Include specific citations with author, year, and DOI when available
- Use professional, supportive language appropriate for mental health contexts
- Never provide medical diagnoses or treatment recommendations
- Suggest consulting qualified mental health professionals for personal guidance
- If the research doesn't address the question directly, be honest about limitations
- Focus on evidence-based practices and validated interventions

Context from research papers:
{$contextText}

Question: {$query}";

        $llm = new LlmService();
        $answer = $llm->generateAnswer($query, $contextText, $systemPrompt);

        $sources = collect($context)->map(function($item) {
            return $item['source'];
        })->unique('title')->values()->toArray();

        return [
            'answer' => $answer ?: $this->generateFallbackResponse($query, $context),
            'sources' => $sources
        ];
    }

    private function generateFallbackResponse(string $query, array $context): string
    {
        if (empty($context)) {
            return "I don't have specific research available on that topic in our current database. For evidence-based information on mental health topics, I recommend consulting with a qualified mental health professional or checking peer-reviewed sources like PubMed, APA journals, or clinical practice guidelines.";
        }

        $response = "Based on the available research in our database:\n\n";

        foreach (array_slice($context, 0, 3) as $item) {
            $source = $item['source'];
            $snippet = substr(strip_tags($item['text']), 0, 200) . "...";
            $response .= "• {$snippet}\n";
            $response .= "  (Source: {$source['title']} by {$source['authors']}, {$source['year']})\n\n";
        }

        $response .= "Please consult with a qualified mental health professional for personalized guidance.";

        return $response;
    }

    private function logChatInteraction(string $query, array $response, string $conversationId): void
    {
        // Log for analytics - could be stored in a chat_logs table
        Log::info('Mental Health Research Chat', [
            'user_id' => auth()->id(),
            'conversation_id' => $conversationId,
            'query' => $query,
            'sources_count' => count($response['sources'] ?? []),
            'timestamp' => now()->toISOString()
        ]);
    }

    private function getChatDashboardData($user): array
    {
        return Cache::remember("chat_dashboard_{$user->id}", 300, function () use ($user) {
            $totalDocs = Document::where('meta->type', 'research_document')
                ->where('meta->verified', true)
                ->count();

            $topicStats = Document::where('meta->type', 'research_document')
                ->where('meta->verified', true)
                ->get()
                ->flatMap(fn($doc) => $doc->meta['topics'] ?? [])
                ->countBy()
                ->sortDesc()
                ->take(5);

            return [
                'total_research_papers' => $totalDocs,
                'top_topics' => $topicStats,
                'user_role' => $user->role,
                'is_verified_provider' => $this->isVerifiedProvider($user),
            ];
        });
    }
}
