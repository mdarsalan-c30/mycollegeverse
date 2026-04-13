<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CollegeController extends Controller
{
    /**
     * Display the Institutional Registry.
     */
    public function index(Request $request)
    {
        $query = College::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
        }

        $colleges = $query->latest()->paginate(15);

        return view('admin.colleges.index', compact('colleges'));
    }

    /**
     * Store a single college node.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:colleges,name',
            'location' => 'required|string',
            'description' => 'required|string',
            'thumbnail_url' => 'nullable|url',
            'tags' => 'nullable|string',
        ]);

        $college = College::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'location' => $request->location,
            'description' => $request->description,
            'thumbnail_url' => $request->thumbnail_url ?? 'https://via.placeholder.com/300?text=MCV+Node',
            'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : [],
            'student_count' => 0,
            'rating' => 5.0,
        ]);

        ApprovalLog::safeCreate([
            'admin_id' => Auth::id(),
            'action' => 'college_registered',
            'target_type' => 'College',
            'target_id' => $college->id,
            'metadata' => ['name' => $college->name],
        ]);

        return back()->with('success', "College Node '{$college->name}' initialized in the multiverse.");
    }

    /**
     * Massive Institutional Injection (Bulk Import) 🚀
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'nullable|file|mimes:csv,txt',
            'paste_data' => 'nullable|string',
        ]);

        $rows = [];
        $importCount = 0;
        $skipCount = 0;

        // Method 1: CSV Upload 📄
        if ($request->hasFile('import_file')) {
            if (($handle = fopen($request->file('import_file')->getRealPath(), "r")) !== FALSE) {
                $header = fgetcsv($handle, 1000, ","); 
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $rows[] = $data;
                }
                fclose($handle);
            }
        } 
        // Method 2: Text/AI Paste 🤖 (Multiple delimiter support)
        elseif ($request->filled('paste_data')) {
            $lines = explode("\n", str_replace("\r", "", $request->paste_data));
            foreach ($lines as $line) {
                if (trim($line)) {
                    // Support |, \t, or simple comma
                    if (str_contains($line, '|')) {
                        $data = explode('|', $line);
                    } elseif (str_contains($line, "\t")) {
                        $data = explode("\t", $line);
                    } else {
                        $data = str_getcsv($line); // Fallback to standard CSV line parsing
                    }
                    $rows[] = array_map('trim', $data);
                }
            }
        }

        foreach ($rows as $row) {
            if (count($row) >= 2) {
                $name = $row[0];
                if (!College::where('name', $name)->exists()) {
                    College::create([
                        'name' => $name,
                        'slug' => Str::slug($name),
                        'location' => $row[1] ?? 'Unknown Node',
                        'description' => $row[2] ?? 'Academic expansion in progress.',
                        'thumbnail_url' => $row[3] ?? 'https://via.placeholder.com/300?text=MCV+Node',
                        'tags' => isset($row[4]) ? array_map('trim', explode(',', $row[4])) : ['General'],
                        'student_count' => 0,
                        'rating' => 5.0,
                    ]);
                    $importCount++;
                } else {
                    $skipCount++;
                }
            }
        }

        ApprovalLog::create([
            'admin_id' => Auth::id(),
            'action' => 'bulk_college_import',
            'target_type' => 'System',
            'target_id' => 0,
            'metadata' => [
                'success' => $importCount,
                'skipped' => $skipCount,
            ],
        ]);

        $message = "Institutional Flux Complete: {$importCount} nodes established.";
        if ($skipCount > 0) $message .= " ({$skipCount} existing nodes skipped).";

        return back()->with('success', $message);
    }

    /**
     * Re-calibrate an institutional node (Update).
     */
    public function update(Request $request, College $college)
    {
        $request->validate([
            'name' => 'required|string|unique:colleges,name,' . $college->id,
            'location' => 'required|string',
            'description' => 'required|string',
            'thumbnail_url' => 'nullable|url',
            'tags' => 'nullable|string',
        ]);

        $oldData = $college->only(['name', 'location', 'description', 'thumbnail_url', 'tags']);
        
        $college->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'location' => $request->location,
            'description' => $request->description,
            'thumbnail_url' => $request->thumbnail_url,
            'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : [],
        ]);

        ApprovalLog::create([
            'admin_id' => Auth::id(),
            'action' => 'college_updated',
            'target_type' => 'College',
            'target_id' => $college->id,
            'metadata' => [
                'old' => $oldData,
                'new' => $college->only(['name', 'location', 'description', 'thumbnail_url', 'tags']),
            ],
        ]);

        return back()->with('success', "Node '{$college->name}' re-calibrated successfully.");
    }

    /**
     * Purge a college node.
     */
    public function destroy(College $college)
    {
        $name = $college->name;
        $id = $college->id;
        $college->delete();

        ApprovalLog::safeCreate([
            'admin_id' => Auth::id(),
            'action' => 'college_purged',
            'target_type' => 'College',
            'target_id' => $id,
            'metadata' => ['name' => $name],
        ]);

        return back()->with('success', "Node '{$name}' has been collapsed and removed.");
    }
}
