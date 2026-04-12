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
        $query = Report::query();

        // High-Fidelity Filtering 🔍
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        } else {
            // Default to Pending for administrative efficiency
            $query->where('status', 'pending');
        }

        if ($request->has('type') && $request->type != 'all') {
            $query->where('reportable_type', $request->type);
        }

        $reports = $query->with(['reporter'])
            ->latest()
            ->paginate(15);

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
        ApprovalLog::create([
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
