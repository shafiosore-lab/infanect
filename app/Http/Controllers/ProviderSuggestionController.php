<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recommendation;

class ProviderSuggestionController extends Controller
{
    public function index(Request $request)
    {
        $providerId = $request->user()->id;
        $query = Recommendation::where(function($q) use ($providerId) {
            $q->whereJsonContains('payload->providers', ['id' => $providerId])
              ->orWhere('payload', 'like', '%"provider_id":'. $providerId .'%');
        });

        if ($since = $request->get('since')) {
            $query->where('created_at', '>=', $since);
        }

        if ($type = $request->get('type')) {
            $query->where('payload->type', $type);
        }

        $results = $query->orderBy('created_at','desc')->paginate((int)$request->get('per_page', 10));

        // Ensure payload is decoded
        $results->getCollection()->transform(function($item){
            if (is_string($item->payload)) {
                $item->payload = json_decode($item->payload, true) ?: [];
            }
            return $item;
        });

        return response()->json($results);
    }
}
