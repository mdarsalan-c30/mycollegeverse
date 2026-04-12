<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display the Citizen Registry.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // High-Fidelity Search 🔍
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filtering by Status/Role if needed
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $users = $query->with(['college'])
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Update the citizen's status (Block/Activate).
     */
    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,banned,suspended',
            'reason' => 'nullable|string|max:500',
        ]);

        $oldStatus = $user->status;
        $user->update([
            'status' => $request->status,
            'ban_reason' => $request->reason,
        ]);

        // Audit Logging 🛡️
        ApprovalLog::create([
            'admin_id' => Auth::id(),
            'action' => 'status_update',
            'target_type' => 'User',
            'target_id' => $user->id,
            'metadata' => [
                'old_status' => $oldStatus,
                'new_status' => $user->status,
                'reason' => $request->reason,
            ],
        ]);

        return back()->with('success', "Citizen '{$user->name}' status updated to {$user->status}.");
    }

    /**
     * Promote a citizen to Contributor.
     */
    public function promote(User $user)
    {
        if ($user->role === 'student') {
            $user->update(['role' => 'contributor']);

            ApprovalLog::create([
                'admin_id' => Auth::id(),
                'action' => 'promotion',
                'target_type' => 'User',
                'target_id' => $user->id,
                'metadata' => ['new_role' => 'contributor'],
            ]);

            return back()->with('success', "Citizen '{$user->name}' promoted to Contributor.");
        }

        return back()->with('error', "Citizen is already a {$user->role}.");
    }
}
