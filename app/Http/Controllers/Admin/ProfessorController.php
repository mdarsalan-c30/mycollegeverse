<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfessorController extends Controller
{
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

        $professor = Professor::create($request->all());

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
