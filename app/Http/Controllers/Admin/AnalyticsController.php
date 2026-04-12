<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Note;
use App\Models\Report;
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
        // 1. Core KPIs 🏁
        $stats = [
            'total_users' => User::count(),
            'total_notes' => Note::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'active_colleges' => College::count(),
        ];

        // 2. High-Fidelity Time-Series Data (Zero-filled for last 30 days) 📊
        $userGrowthData = [];
        $noteFluxData = [];
        $labels = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $labels[] = Carbon::today()->subDays($i)->format('M d');
            
            // Citizen Registrations
            $userGrowthData[] = User::whereDate('created_at', $date)->count();
            
            // Knowledge Asset Flux
            $noteFluxData[] = Note::whereDate('created_at', $date)->count();
        }

        // 3. Institutional Heatmap (Activity per College) 🏛️
        $collegeActivity = College::select('name', 'student_count')
            ->orderBy('student_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.analytics.index', compact('stats', 'userGrowthData', 'noteFluxData', 'labels', 'collegeActivity'));
    }
}
