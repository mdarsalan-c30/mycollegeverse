<?php

namespace App\Http\Controllers\Admin;

 Ame;
use App\Http\Controllers\Controller;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MediaSearchController extends Controller
{
    /**
     * Search WikiMedia Commons for Institutional Identity.
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        
        if (empty($query)) return response()->json([]);

        // Strip "University" or "College" common suffixes for broader search if needed
        $cleanQuery = trim(preg_replace('/\b(University|College|Institute|of|In)\b/i', '', $query));

        return Cache::remember('wiki_search_' . md5($query), 3600, function() use ($query, $cleanQuery) {
            try {
                // Strategy: Search for Name + Keywords for better campus architecture results 🗺️
                $searchTerms = [
                    $query,
                    $query . " Building",
                    $query . " Campus",
                    $cleanQuery . " University",
                ];

                $results = [];

                foreach ($searchTerms as $term) {
                    $response = Http::withUserAgent('MyCollegeVerse/1.0 (contact@mycollegeverse.in)')
                        ->get('https://commons.wikimedia.org/w/api.php', [
                            'action' => 'query',
                            'format' => 'json',
                            'generator' => 'search',
                            'gsrsearch' => $term,
                            'gsrnamespace' => 6, // File namespace
                            'gsrlimit' => 5,
                            'prop' => 'imageinfo',
                            'iiprop' => 'url|size|mime',
                            'iiurlwidth' => 800,
                        ]);

                    if ($response->successful()) {
                        $pages = $response->json('query.pages', []);
                        foreach ($pages as $page) {
                            if (isset($page['imageinfo'][0])) {
                                $info = $page['imageinfo'][0];
                                if (isset($info['url']) && str_contains($info['mime'], 'image/')) {
                                    $results[] = [
                                        'title' => $page['title'],
                                        'url' => $info['url'],
                                        'thumb' => $info['thumburl'] ?? $info['url'],
                                    ];
                                }
                            }
                        }
                    }

                    // If we have 3+ good results, stop searching broader terms 🛡️
                    if (count($results) >= 3) break;
                }

                // De-duplicate results by URL 🛰️
                $uniqueResults = [];
                $seenUrls = [];
                foreach ($results as $item) {
                    if (!in_array($item['url'], $seenUrls)) {
                        $uniqueResults[] = $item;
                        $seenUrls[] = $item['url'];
                    }
                }

                return $uniqueResults;
            } catch (\Exception $e) {
                return [];
            }
        });
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
