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
            'type' => 'required|string|in:' . implode(',', config('college_metadata.types')),
            'streams' => 'required|array',
            'state' => 'required|string|in:' . implode(',', config('college_metadata.states')),
            'city' => 'required|string',
            'location' => 'required|string',
            'description' => 'required|string',
            'campusimg' => 'nullable|url',
            'tags' => 'nullable|string',
        ]);

        $college = College::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'streams' => $request->streams,
            'state' => $request->state,
            'city' => $request->city,
            'location' => $request->location,
            'description' => $request->description,
            'campusimg' => $request->campusimg ?? 'https://via.placeholder.com/600?text=Campus+Identity',
            'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : [],
            'student_count' => rand(1000, 5000),
            'rating' => 0.0,
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
     * Format: Name | Type | Streams | State | City | Location | Description | LogoURL | Tags
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

        try {
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
            // Method 2: Text/AI Paste 🤖
            elseif ($request->filled('paste_data')) {
                $lines = explode("\n", str_replace("\r", "", $request->paste_data));
                foreach ($lines as $line) {
                    if (trim($line)) {
                        if (str_contains($line, '|')) {
                            $data = explode('|', $line);
                        } elseif (str_contains($line, "\t")) {
                            $data = explode("\t", $line);
                        } else {
                            $data = str_getcsv($line);
                        }
                        $rows[] = array_map('trim', $data);
                    }
                }
            }

            foreach ($rows as $row) {
                // Formatting Check: Standard 9-column flux 🛡️
                // Order: Name | Type | State | City | Streams | Location | Description | LogoURL | Tags
                if (count($row) >= 1) {
                    $name = trim($row[0]);
                    
                    // Code Guard: Skip accidental PHP code lines
                    if (str_contains($name, '=>') || str_contains($name, '[')) continue;

                    if (!College::where('name', $name)->exists()) {
                        $type = $row[1] ?? 'Private';
                        $state = $row[2] ?? 'Unknown';
                        $city = $row[3] ?? 'Unknown';
                        
                        // Handle Streams (Comma separated -> Array)
                        $streams = isset($row[4]) ? array_map('trim', explode(',', $row[4])) : ['General'];
                        
                        $location = $row[5] ?? 'Unknown Node';
                        $description = $row[6] ?? 'Institutional narrative in development.';
                        $logo = $row[7] ?? 'https://via.placeholder.com/600?text=Campus+Identity';
                        
                        // Handle Tags (Comma separated -> Array)
                        $tags = isset($row[8]) ? array_map('trim', explode(',', $row[8])) : ['General'];

                        College::create([
                            'name' => $name,
                            'slug' => Str::slug($name),
                            'type' => $type,
                            'streams' => $streams,
                            'state' => $state,
                            'city' => $city,
                            'location' => $location,
                            'description' => $description,
                            'campusimg' => $logo,
                            'tags' => $tags,
                            'student_count' => rand(1000, 5000),
                            'rating' => 0.0, // Initialized as 0.0, will sync from student reviews
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
                'metadata' => ['success' => $importCount, 'skipped' => $skipCount],
            ]);

            return back()->with('success', "Institutional Flux Complete: {$importCount} nodes established.");

        } catch (\Exception $e) {
            return back()->with('error', "Injection Failed: " . $e->getMessage());
        }
    }

    /**
     * Re-calibrate an institutional node (Update).
     */
    public function update(Request $request, College $college)
    {
        $request->validate([
            'name' => 'required|string|unique:colleges,name,' . $college->id,
            'type' => 'required|string|in:' . implode(',', config('college_metadata.types')),
            'streams' => 'required|array',
            'state' => 'required|string|in:' . implode(',', config('college_metadata.states')),
            'city' => 'required|string',
            'location' => 'required|string',
            'description' => 'required|string',
            'thumbnail_url' => 'nullable|url',
            'tags' => 'nullable|string',
        ]);

        $oldData = $college->toArray();
        
        $college->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'streams' => $request->streams ?? [],
            'state' => $request->state,
            'city' => $request->city,
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
                'new' => $college->toArray(),
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
