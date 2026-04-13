<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Note;
use App\Models\College;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display the Deep Analytics Command Terminal.
     */
    public function index()
    {
        // 1. Core KPIs 🏁 — using DB::table for missing-table safety
        $stats = [
            'total_users'    => $this->safe(fn() => User::count(), 0),
            'total_notes'    => $this->safe(fn() => Note::count(), 0),
            'pending_reports'=> $this->safe(fn() => DB::table('reports')->where('status', 'pending')->count(), 0),
            'active_colleges'=> $this->safe(fn() => College::count(), 0),
        ];

        // 2. High-Fidelity Time-Series Data (Zero-filled for last 30 days) 📊
        $userGrowthData = [];
        $noteFluxData = [];
        $labels = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $labels[] = Carbon::today()->subDays($i)->format('M d');
            $userGrowthData[] = $this->safe(fn() => User::whereDate('created_at', $date)->count(), 0);
            $noteFluxData[]   = $this->safe(fn() => Note::whereDate('created_at', $date)->count(), 0);
        }

        // 3. Institutional Heatmap (Activity per College) 🏛️
        $collegeActivity = $this->safe(fn() =>
            College::select('name', DB::raw('COALESCE(student_count, 0) as student_count'))
                ->orderBy('student_count', 'desc')
                ->limit(10)
                ->get(),
            collect([])
        );

        return view('admin.analytics.index', compact('stats', 'userGrowthData', 'noteFluxData', 'labels', 'collegeActivity'));
    }

    private function safe(callable $fn, $default)
    {
        try {
            return $fn();
        } catch (\Throwable $e) {
            \Log::warning('Analytics fetch failed: ' . $e->getMessage());
            return $default;
        }
    }
}

