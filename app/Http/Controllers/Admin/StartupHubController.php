<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Page;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StartupHubController extends Controller
{
    /**
     * Display the Startup Hub Command Center.
     * 🛡️ Zero-Crash Logic: Uses safe fallbacks for all data points.
     */
    public function index()
    {
        // Institutional Pages Node 🏛️
        $slugs = ['about-us', 'careers', 'partner', 'faq'];
        $pages = Page::whereIn('slug', $slugs)->get()->keyBy('slug');

        // Social Matrix Node 📡
        $socials = [
            'instagram' => Setting::get('instagram_link', 'https://www.instagram.com/mycollegeverse.xyz/'),
            'reddit'    => Setting::get('reddit_link', '#'),
            'x_social'  => Setting::get('x_link', '#'),
            'youtube'   => Setting::get('youtube_link', '#'),
            'contact_email' => Setting::get('contact_email', 'mycollegeverse@gmail.com'),
        ];

        return view('admin.startup.index', compact('pages', 'socials', 'slugs'));
    }

    /**
     * Synchronize the Social Matrix.
     */
    public function updateSocial(Request $request)
    {
        $validated = $request->validate([
            'instagram_link' => 'nullable|string',
            'reddit_link'    => 'nullable|string',
            'x_link'         => 'nullable|string',
            'youtube_link'   => 'nullable|string',
            'contact_email'  => 'nullable|email',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value ?? '#');
        }

        ApprovalLog::safeCreate([
            'admin_id'    => Auth::id(),
            'action'      => 'social_matrix_updated',
            'target_type' => 'StartupHub',
            'target_id'   => 0,
            'metadata'    => ['keys' => array_keys($validated)],
        ]);

        return back()->with('success', 'Social Matrix has been synchronized across the multiverse.');
    }
}
