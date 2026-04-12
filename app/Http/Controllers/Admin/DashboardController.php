<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Note;
use App\Models\Report;
use App\Models\Post;
use App\Models\College;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the Admin 'Control Tower' Overview.
     */
    public function index()
    {
        // High-Fidelity Pulse Metrics 📡
        $stats = [
            'total_users' => User::count(),
            'active_today' => User::whereDate('updated_at', Carbon::today())->count(),
            'total_notes' => Note::count(),
            'pending_notes' => Note::where('is_verified', false)->count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'total_colleges' => College::count(),
        ];

        // Growth Trends (Last 7 Days) 📈
        $userGrowth = User::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Latest Activity 🚨
        $latestReports = Report::with(['reporter'])->latest()->take(5)->get();
        $latestNotes = Note::with(['user', 'college'])->latest()->take(5)->get();

        return view('admin.index', compact('stats', 'userGrowth', 'latestReports', 'latestNotes'));
    }
}
