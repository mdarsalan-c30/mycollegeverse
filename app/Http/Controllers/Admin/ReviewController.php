<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollegeReview;
use App\Models\Review;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display the Feedback Governance Hub.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'college');
        $search = $request->get('search');

        if ($type === 'professor') {
            $query = Review::query()->with(['user', 'professor']);
        } else {
            $query = CollegeReview::query()->with(['user', 'college']);
        }

        if ($search) {
            $query->where('comment', 'like', "%{$search}%");
        }

        $reviews = $query->latest()->paginate(15);

        if ($reviews->isEmpty()) {
            session()->flash('info', "Observation Registry is currently blank for '{$type}' feedback signals.");
        }

        return view('admin.reviews.index', compact('reviews', 'type'));
    }

    /**
     * Purge a specific feedback node.
     */
    public function destroy($type, $id)
    {
        $review = $type === 'professor' ? Review::findOrFail($id) : CollegeReview::findOrFail($id);
        
        $targetName = ($type === 'professor') 
            ? optional($review->professor)->name ?? 'Legacy Advisor'
            : optional($review->college)->name ?? 'Legacy Institution';
        
        // Log before deletion
        // Audit Logging 🛡️
        ApprovalLog::safeCreate([
            'admin_id' => Auth::id(),
            'action' => 'review_purged',
            'target_type' => 'Review',
            'target_id' => $review->id,
            'metadata' => [
                'type' => $type,
                'user' => optional($review->user)->name ?? 'Unknown',
                'target' => $targetName,
            ],
        ]);

        $review->delete();

        return back()->with('success', "Feedback node for '{$targetName}' has been dissolved.");
    }
}
