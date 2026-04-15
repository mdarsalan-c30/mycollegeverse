<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::latest()->paginate(10);
        return view('admin.rewards.index', compact('rewards'));
    }

    public function create()
    {
        return view('admin.rewards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'karma_required' => 'required|integer|min:0',
            'claim_link' => 'required|url',
            'max_usage' => 'required|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        Reward::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'karma_required' => $validated['karma_required'],
            'claim_link' => $validated['claim_link'],
            'max_usage' => $validated['max_usage'],
            'expires_at' => $validated['expires_at'],
            'is_active' => $request->has('is_active') ? $request->is_active : true,
        ]);

        return redirect()->route('admin.rewards.index')->with('success', 'Reward manifested in the multiverse! 🎁');
    }

    public function edit(Reward $reward)
    {
        return view('admin.rewards.edit', compact('reward'));
    }

    public function update(Request $request, Reward $reward)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'karma_required' => 'required|integer|min:0',
            'claim_link' => 'required|url',
            'max_usage' => 'required|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $reward->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'karma_required' => $validated['karma_required'],
            'claim_link' => $validated['claim_link'],
            'max_usage' => $validated['max_usage'],
            'expires_at' => $validated['expires_at'],
            'is_active' => $request->has('is_active') ? $request->is_active : (bool)$request->input('is_active', true),
        ]);

        return redirect()->route('admin.rewards.index')->with('success', 'Reward synchronization complete. 🌀');
    }

    public function destroy(Reward $reward)
    {
        $reward->delete();
        return back()->with('success', 'Reward purged from the system.');
    }
}
