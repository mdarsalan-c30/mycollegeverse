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

        if ($type === 'professor') {
            $query = Review::query()->with(['user', 'professor']);
        } else {
    public function index()
    {
        $reviews = Review::with(['user', 'professor'])->latest()->paginate(15);
        
        if ($reviews->isEmpty()) {
            session()->flash('info', 'Observation queue empty. No student feedback nodes detected.');
        }
        
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Purge a specific feedback node.
     */
    public function destroy($type, $id)
    {
        $review = $type === 'professor' ? Review::findOrFail($id) : CollegeReview::findOrFail($id);
        $targetName = $type === 'professor' ? $review->professor->name : $review->college->name;
        
        // Log before deletion
        // Audit Logging 🛡️
        ApprovalLog::safeCreate([
            'admin_id' => Auth::id(),
            'action' => 'review_purged',
            'target_type' => 'Review',
            'target_id' => $review->id,
            'metadata' => [
                'user' => optional($review->user)->name ?? 'Unknown',
                'professor' => optional($review->professor)->name ?? 'Unknown',
            ],
        ]);

        $review->delete();

        return back()->with('success', "Feedback node for '{$targetName}' has been collapsed.");
    }
}
