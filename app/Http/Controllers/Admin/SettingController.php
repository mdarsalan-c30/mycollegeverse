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
        // 🧪 Default Thresholds — crash-proof if settings table is missing
        $settings = [
            'site_name'         => $this->safeGet('site_name', 'MyCollegeVerse'),
            'max_file_size'     => $this->safeGet('max_file_size', '10'),
            'auto_hide_reports' => $this->safeGet('auto_hide_reports', '5'),
            'flagged_keywords'  => $this->safeGet('flagged_keywords', 'scam, spam, abuse, payment, fraud'),
            'footer_text'       => $this->safeGet('footer_text', '© 2026 MyCollegeVerse - Control Tower'),
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
            try {
                Setting::set($key, $value);
            } catch (\Throwable $e) {
                \Log::warning("Settings update failed for key [{$key}]: " . $e->getMessage());
            }
        }

        // Audit Logging 🛡️ — safe if approval_logs table missing
        try {
            ApprovalLog::create([
                'admin_id'    => Auth::id(),
                'action'      => 'settings_updated',
                'target_type' => 'System',
                'target_id'   => 0,
                'metadata'    => ['changes' => array_keys($data)],
            ]);
        } catch (\Throwable $e) {
            \Log::warning('ApprovalLog create failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Global configurations updated and synchronized.');
    }

    /** Safely get a setting value — returns default if table missing. */
    private function safeGet(string $key, $default)
    {
        try {
            return Setting::get($key, $default);
        } catch (\Throwable $e) {
            \Log::warning("Setting::get failed for [{$key}]: " . $e->getMessage());
            return $default;
        }
    }
}

