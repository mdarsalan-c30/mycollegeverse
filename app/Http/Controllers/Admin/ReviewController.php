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
            $query = CollegeReview::query()->with(['user', 'college']);
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('comment', 'like', "%{$search}%");
        }

        $reviews = $query->latest()->paginate(15);

        return view('admin.reviews.index', compact('reviews', 'type'));
    }

    /**
     * Purge a specific feedback node.
     */
    public function destroy($type, $id)
    {
        $review = $type === 'professor' ? Review::findOrFail($id) : CollegeReview::findOrFail($id);
        $targetName = $type === 'professor' ? $review->professor->name : $review->college->name;
        
        // Log before deletion
        ApprovalLog::create([
            'admin_id' => Auth::id(),
            'action' => 'review_purged',
            'target_type' => ucfirst($type) . 'Review',
            'target_id' => $id,
            'metadata' => [
                'comment' => $review->comment,
                'target' => $targetName,
                'user' => $review->user->name
            ],
        ]);

        $review->delete();

        return back()->with('success', "Feedback node for '{$targetName}' has been collapsed.");
    }
}
