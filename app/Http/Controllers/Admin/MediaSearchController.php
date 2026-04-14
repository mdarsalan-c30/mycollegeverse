<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MediaSearchController extends Controller
{
    /**
     * Hyper-Discovery Search for Institutional Identity.
     * Uses Wikipedia 'PageImages' for official campus visuals.
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        
        if (empty($query)) return response()->json([]);

        // Clear previous "empty" cache to force deep scan 🛰️
        Cache::forget('wiki_search_' . md5($query));

        return Cache::remember('wiki_search_' . md5($query), 3600, function() use ($query) {
            $results = [];

            // Stage 1: Search Wikipedia (Official Lead Images) 🏛️
            $results = array_merge($results, $this->fetchFromWiki('en.wikipedia.org', $query));

            // Stage 2: Search Wikimedia Commons (Campus Photography) 🏢
            if (count($results) < 3) {
                $results = array_merge($results, $this->fetchFromWiki('commons.wikimedia.org', $query));
            }

            return collect($results)->unique('url')->values()->all();
        });
    }

    /**
     * High-Fidelity Wiki Fetch Utility.
     */
    private function fetchFromWiki($domain, $query)
    {
        try {
            // Step A: Locate the Page Identity 🛡️
            $searchResponse = Http::withUserAgent('MyCollegeVerse/1.0 (contact@mycollegeverse.in)')
                ->get("https://{$domain}/w/api.php", [
                    'action' => 'query',
                    'list' => 'search',
                    'srsearch' => $query,
                    'srlimit' => 5,
                    'format' => 'json'
                ]);

            if ($searchResponse->failed()) return [];

            $searchData = $searchResponse->json('query.search', []);
            $results = [];

            foreach ($searchData as $searchItem) {
                $title = $searchItem['title'];

                // Step B: Extract Representative 'PageImage' 🛰️
                $imageResponse = Http::withUserAgent('MyCollegeVerse/1.0 (contact@mycollegeverse.in)')
                    ->get("https://{$domain}/w/api.php", [
                        'action' => 'query',
                        'format' => 'json',
                        'titles' => $title,
                        'prop' => 'pageimages|imageinfo',
                        'pithumbsize' => 1000, // High-fidelity 4K-ish thumbnails
                        'iiprop' => 'url',
                    ]);

                $pages = $imageResponse->json('query.pages', []);
                foreach ($pages as $page) {
                    if (isset($page['thumbnail'])) {
                        $results[] = [
                            'title' => $title,
                            'url' => $page['thumbnail']['source'],
                            'thumb' => $page['thumbnail']['source'],
                            'source' => $domain
                        ];
                    }
                }
            }

            return $results;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Calibrate the node with a chosen image URL.
     */
    public function update(Request $request, College $college)
    {
        $request->validate([
            'thumbnail_url' => 'required|url',
        ]);

        $college->update([
            'thumbnail_url' => $request->thumbnail_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Institutional identity re-calibrated successfully.',
            'thumbnail_url' => $college->thumbnail_url
        ]);
    }
}
