<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Note;
use App\Models\College;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the Admin 'Control Tower' Overview.
     */
    public function index()
    {
        // High-Fidelity Pulse Metrics 📡 — wrapped in try/catch for missing tables on production
        $stats = [
            'total_users'    => $this->safeCount(fn() => User::count()),
            'active_today'   => $this->safeCount(fn() => User::whereDate('updated_at', Carbon::today())->count()),
            'total_notes'    => $this->safeCount(fn() => Note::count()),
            'pending_notes'  => $this->safeCount(fn() => Note::where('is_verified', false)->count()),
            'pending_reports'=> $this->safeCount(fn() => DB::table('reports')->where('status', 'pending')->count()),
            'total_colleges' => $this->safeCount(fn() => College::count()),
        ];

        // Growth Trends (Last 7 Days) 📈
        $userGrowth = $this->safeFetch(fn() =>
            User::selectRaw('DATE(created_at) as date, count(*) as count')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        );

        // Latest Activity 🚨
        $latestReports = $this->safeFetch(fn() =>
            DB::table('reports')
                ->join('users', 'users.id', '=', 'reports.reporter_id')
                ->select('reports.*', 'users.name as reporter_name')
                ->latest('reports.created_at')
                ->take(5)
                ->get()
                ->map(function($r) {
                    $r->reporter = (object)['name' => $r->reporter_name];
                    $r->created_at = Carbon::parse($r->created_at);
                    return $r;
                })
        );

        $latestNotes = $this->safeFetch(fn() =>
            Note::with(['user', 'college'])->latest()->take(5)->get()
        );

        return view('admin.index', compact('stats', 'userGrowth', 'latestReports', 'latestNotes'));
    }

    /** Safely run a count query — returns 0 if table missing. */
    private function safeCount(callable $fn): int
    {
        try {
            return (int) $fn();
        } catch (\Throwable $e) {
            \Log::warning('Admin Dashboard count failed: ' . $e->getMessage());
            return 0;
        }
    }

    /** Safely run a fetch query — returns empty collection if table missing. */
    private function safeFetch(callable $fn)
    {
        try {
            return $fn();
        } catch (\Throwable $e) {
            \Log::warning('Admin Dashboard fetch failed: ' . $e->getMessage());
            return collect([]);
        }
    }
}
