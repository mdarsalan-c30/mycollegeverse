<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use App\Models\ApprovalLog;
use App\Models\ProfessorRequest;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfessorController extends Controller
{
    /**
     * Display the Faculty Moderation Queue.
     */
    public function requests()
    {
        $requests = ProfessorRequest::with('user.college')->latest()->paginate(15);
        $colleges = College::orderBy('name')->get();
        return view('admin.professors.requests', compact('requests', 'colleges'));
    }

    /**
     * Approve a faculty request and establish a new node.
     */
    public function approveRequest(Request $request, ProfessorRequest $profRequest)
    {
        $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'name' => 'required|string',
            'department' => 'required|string',
        ]);

        try {
            return \DB::transaction(function () use ($request, $profRequest) {
                // 1. Create the actual Professor node
                $professor = Professor::create([
                    'name' => $request->name,
                    'department' => $request->department,
                    'college_id' => $request->college_id,
                    'profile_pic' => $profRequest->profile_photo_url,
                    'slug' => Str::slug($request->name . '-' . $request->department . '-' . time()),
                ]);

                // 2. Update Request Status
                $profRequest->update(['status' => 'approved']);

                // 3. Log the administrative action
                ApprovalLog::safeCreate([
                    'admin_id' => Auth::id(),
                    'action' => 'professor_request_approved',
                    'target_type' => 'Professor',
                    'target_id' => $professor->id,
                    'metadata' => [
                        'request_id' => $profRequest->id,
                        'professor_name' => $professor->name,
                        'college_id' => $request->college_id
                    ],
                ]);

                return redirect()->route('admin.professors.requests')->with('success', "Faculty Node '{$professor->name}' established from request.");
            });
        } catch (\Exception $e) {
            return back()->with('error', "Failed to establish faculty node: " . $e->getMessage());
        }
    }

    /**
     * Reject a faculty request.
     */
    public function rejectRequest(ProfessorRequest $profRequest)
    {
        $profRequest->update(['status' => 'rejected']);

        ApprovalLog::safeCreate([
            'admin_id' => Auth::id(),
            'action' => 'professor_request_rejected',
            'target_type' => 'ProfessorRequest',
            'target_id' => $profRequest->id,
            'metadata' => ['professor_name' => $profRequest->professor_name],
        ]);

        return redirect()->route('admin.professors.requests')->with('success', "Request for '{$profRequest->professor_name}' rejected.");
    }

    /**
     * Display the Faculty Registry.
     */
    public function index(Request $request)
    {
        $query = Professor::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
        }

        $professors = $query->with('college')->latest()->paginate(15);

        return view('admin.professors.index', compact('professors'));
    }

    /**
     * Store a new faculty node.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'department' => 'required|string',
            'college_id' => 'required|exists:colleges,id',
            'profile_pic' => 'nullable|url',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name . '-' . $request->department . '-' . time());
        $professor = Professor::create($data);

        ApprovalLog::safeCreate([
            'admin_id' => Auth::id(),
            'action' => 'professor_created',
            'target_type' => 'Professor',
            'target_id' => $professor->id,
            'metadata' => ['name' => $professor->name],
        ]);

        return back()->with('success', "Faculty Node '{$professor->name}' established.");
    }

    /**
     * Purge a faculty node.
     */
    public function destroy(Professor $professor)
    {
        $name = $professor->name;
        $id = $professor->id;
        $professor->delete();

        ApprovalLog::safeCreate([
            'admin_id' => Auth::id(),
            'action' => 'professor_purged',
            'target_type' => 'Professor',
            'target_id' => $id,
            'metadata' => ['name' => $name],
        ]);

        return back()->with('success', "Faculty Node '{$name}' dissolved.");
    }
}
