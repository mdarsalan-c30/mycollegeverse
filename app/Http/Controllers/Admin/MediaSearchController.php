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

        return Cache::remember('wiki_search_' . md5($query), 3600, function() use ($query) {
            try {
                $response = Http::withUserAgent('MyCollegeVerse/1.0 (contact@mycollegeverse.in)')
                    ->get('https://commons.wikimedia.org/w/api.php', [
                        'action' => 'query',
                        'format' => 'json',
                        'generator' => 'search',
                        'gsrsearch' => "filetype:bitmap " . $query,
                        'gsrnamespace' => 6, // File namespace
                        'gsrlimit' => 10,
                        'prop' => 'imageinfo',
                        'iiprop' => 'url|size|mime',
                        'iiurlwidth' => 600, // Get high-res thumbnails
                    ]);

                if ($response->failed()) return [];

                $pages = $response->json('query.pages', []);
                $results = [];

                foreach ($pages as $page) {
                    if (isset($page['imageinfo'][0])) {
                        $info = $page['imageinfo'][0];
                        // Filter out small or non-representative images if possible
                        if (str_contains($info['mime'], 'image/')) {
                            $results[] = [
                                'title' => $page['title'],
                                'url' => $info['url'],
                                'thumb' => $info['thumburl'] ?? $info['url'],
                            ];
                        }
                    }
                }

                return $results;
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
