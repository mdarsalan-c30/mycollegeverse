<?php

namespace App\Http\Controllers;

use App\Models\UserEducation;
use App\Models\UserExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    /**
     * Store professional experience.
     */
    public function storeExperience(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'type' => 'required|in:Full-time,Internship,Freelance,Contract',
            'duration' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000'
        ]);

        UserExperience::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'company' => $request->company,
            'type' => $request->type,
            'duration' => $request->duration,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Professional milestone added to your Verse Profile!');
    }

    /**
     * Store educational milestone.
     */
    public function storeEducation(Request $request)
    {
        $request->validate([
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'year' => 'required|string|max:100'
        ]);

        UserEducation::create([
            'user_id' => Auth::id(),
            'institution' => $request->institution,
            'degree' => $request->degree,
            'field_of_study' => $request->field_of_study,
            'year' => $request->year,
        ]);

        return back()->with('success', 'Academic milestone manifested!');
    }

    public function destroyExperience(UserExperience $experience)
    {
        if ($experience->user_id !== Auth::id()) abort(403);
        $experience->delete();
        return back()->with('success', 'Milestone cleared.');
    }

    public function destroyEducation(UserEducation $education)
    {
        if ($education->user_id !== Auth::id()) abort(403);
        $education->delete();
        return back()->with('success', 'Academic record cleared.');
    }
}
