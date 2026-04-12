<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    /**
     * Display the Command Admin Registry.
     */
    public function index()
    {
        $admins = User::where('role', 'admin')->latest()->get();
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Establish a new administrative identity.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Audit Logging 🛡️
        ApprovalLog::create([
            'admin_id' => Auth::id(),
            'action' => 'admin_created',
            'target_type' => 'User',
            'target_id' => $admin->id,
            'metadata' => ['role' => 'admin', 'email' => $admin->email],
        ]);

        return back()->with('success', "Administrative node for '{$admin->name}' has been initialized.");
    }

    /**
     * Revoke administrative authority.
     */
    public function destroy(User $admin)
    {
        if ($admin->id === Auth::id()) {
            return back()->with('error', 'Self-destruction of administrative identity is prohibited.');
        }

        $name = $admin->name;
        $admin->delete();

        ApprovalLog::create([
            'admin_id' => Auth::id(),
            'action' => 'admin_removed',
            'target_type' => 'User',
            'target_id' => $admin->id,
            'metadata' => ['role' => 'admin', 'name' => $name],
        ]);

        return back()->with('success', "Authority for '{$name}' has been revoked and the node collapsed.");
    }
}
