<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display the Resolution Center Queue.
     */
    public function index(Request $request)
    {
        try {
            $query = \DB::table('reports')->join('users', 'users.id', '=', 'reports.reporter_id')
                ->select('reports.*', 'users.name as reporter_name');

            if ($request->has('status') && $request->status != 'all') {
                $query->where('reports.status', $request->status);
            } else {
                $query->where('reports.status', 'pending');
            }

            if ($request->has('type') && $request->type != 'all') {
                $query->where('reports.reportable_type', $request->type);
            }

            $rawReports = $query->latest('reports.created_at')->paginate(15);

            // Map to objects the view expects
            $reports = $rawReports->through(function($r) {
                $r->reporter = (object)['name' => $r->reporter_name];
                $r->created_at = \Carbon\Carbon::parse($r->created_at);
                return $r;
            });
        } catch (\Throwable $e) {
            \Log::warning('Reports table issue: ' . $e->getMessage());
            $reports = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
        }

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Resolve or Ignore a platform security flag.
     */
    public function resolve(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:resolved,ignored',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        // Audit Logging 🛡️
        ApprovalLog::safeCreate([
            'admin_id' => Auth::id(),
            'action' => 'report_resolution',
            'target_type' => 'Report',
            'target_id' => $report->id,
            'metadata' => [
                'type' => $report->reportable_type,
                'resolution' => $request->status,
                'admin_notes' => $request->admin_notes,
            ],
        ]);

        return back()->with('success', "Security Flag resolved as: {$request->status}");
    }
}
