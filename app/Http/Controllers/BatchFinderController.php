<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BatchFinderController extends Controller
{
    /**
     * Display the batch mates for a specific college and year.
     */
    public function index(College $college, $year)
    {
        $batchmates = User::where('college_id', $college->id)
            ->where('year', $year)
            ->where('is_batch_visible', true)
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        return view('colleges.batchmates', compact('college', 'year', 'batchmates'));
    }

    /**
     * Toggle visibility in the Batch Finder. 🛡️
     */
    public function toggleVisibility(Request $request)
    {
        $user = Auth::user();
        
        $user->update([
            'is_batch_visible' => $request->has('visible')
        ]);

        return back()->with('success', $user->is_batch_visible 
            ? 'Visibility Manifested! You are now discoverable by your peers.' 
            : 'Identity Cloaked. You are no longer visible in batch searches.');
    }
}
