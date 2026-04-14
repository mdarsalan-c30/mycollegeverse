<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MediaSearchController extends Controller
{
    /**
     * Hyper-Discovery Search for Institutional Identity.
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        
        if (empty($query)) return response()->json(['error' => 'Query is empty']);

        // Clear previous "empty" cache to force deep scan 🛰️
        Cache::forget('wiki_search_' . md5($query));

        try {
            $results = [];

            // Stage 1: Wikipedia PageImages (Best for official logos/main buildings)
            $results = array_merge($results, $this->fetchFromWiki('en.wikipedia.org', $query));

            // Stage 2: Wikimedia Commons Search (Best for photography)
            if (count($results) < 3) {
                $results = array_merge($results, $this->fetchFromWiki('commons.wikimedia.org', $query, true));
            }

            $final = collect($results)->unique('url')->values()->all();

            if (empty($final)) {
                return response()->json([
                    'message' => 'No visual nodes located',
                    'debug' => [
                        'query' => $query,
                        'server_time' => now()->toDateTimeString(),
                        'hint' => 'Try a broader name or search Google'
                    ]
                ]);
            }

            return response()->json($final);

        } catch (\Exception $e) {
            Log::error("WikiSearch Error: " . $e->getMessage());
            return response()->json([
                'error' => 'Subspace communication error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * High-Fidelity Wiki Fetch Utility.
     */
    private function fetchFromWiki($domain, $query, $isCommons = false)
    {
        try {
            // Use opensearch for more resilient title finding 🛡️
            $searchResponse = Http::withUserAgent('MyCollegeVerse/1.0 (contact@mycollegeverse.in)')
                ->timeout(10)
                ->get("https://{$domain}/w/api.php", [
                    'action' => 'opensearch',
                    'search' => $query,
                    'limit' => 5,
                    'format' => 'json'
                ]);

            if ($searchResponse->failed()) return [];

            $titles = $searchResponse->json(1, []);
            if (empty($titles)) return [];

            $results = [];

            // Fetch images for found titles
            $imageResponse = Http::withUserAgent('MyCollegeVerse/1.0 (contact@mycollegeverse.in)')
                ->timeout(10)
                ->get("https://{$domain}/w/api.php", [
                    'action' => 'query',
                    'format' => 'json',
                    'titles' => implode('|', $titles),
                    'prop' => 'pageimages|imageinfo',
                    'pithumbsize' => 1000,
                    'iiprop' => 'url',
                ]);

            if ($imageResponse->successful()) {
                $pages = $imageResponse->json('query.pages', []);
                foreach ($pages as $page) {
                    if (isset($page['thumbnail'])) {
                        $results[] = [
                            'title' => $page['title'],
                            'url' => $page['thumbnail']['source'],
                            'thumb' => $page['thumbnail']['source'],
                            'source' => $domain
                        ];
                    }
                }
            }

            return $results;
        } catch (\Exception $e) {
            Log::error("Wiki Fetch Failure [$domain]: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Calibrate the node with a chosen image URL.
     */
    public function update(Request $request, College $college)
    {
        $request->validate([
            'campusimg' => 'required|url',
        ]);

        $college->update([
            'campusimg' => $request->campusimg,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Campus identity re-calibrated successfully.',
            'campusimg' => $college->campusimg
        ]);
    }
}
