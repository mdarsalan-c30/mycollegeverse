<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Display the System Command Cluster (Settings).
     */
    public function index()
    {
        // 🧪 Default Thresholds
        $settings = [
            'site_name' => Setting::get('site_name', 'MyCollegeVerse'),
            'max_file_size' => Setting::get('max_file_size', '10'), // MB
            'auto_hide_reports' => Setting::get('auto_hide_reports', '5'),
            'flagged_keywords' => Setting::get('flagged_keywords', 'scam, spam, abuse, payment, fraud'),
            'footer_text' => Setting::get('footer_text', '© 2026 MyCollegeVerse - Control Tower'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update global system configurations.
     */
    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        // Audit Logging 🛡️
        ApprovalLog::create([
            'admin_id' => Auth::id(),
            'action' => 'settings_updated',
            'target_type' => 'System',
            'target_id' => 0,
            'metadata' => ['changes' => array_keys($data)],
        ]);

        return back()->with('success', 'Global configurations updated and synchronized.');
    }
}
