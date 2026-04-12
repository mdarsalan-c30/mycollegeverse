<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display the Chat Monitoring Terminal.
     * Only flagged or suspicious interactions are surfaced for privacy.
     */
    public function index(Request $request)
    {
        // 🧪 Flagged Keywords (Scam, Spam, Abuse, Payment interference)
        $keywords = ['scam', 'spam', 'abuse', 'payment', 'money', 'buy', 'sell', 'fraud', 'fuck', 'shit'];
        
        $query = ChatMessage::query()->with(['sender']);

        // Surface messages containing any flagged keyword
        $query->where(function($q) use ($keywords) {
            foreach ($keywords as $word) {
                $q->orWhere('message', 'like', "%{$word}%");
            }
        });

        // Search within flagged terminal
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('message', 'like', "%{$search}%");
        }

        $messages = $query->latest()->paginate(25);

        return view('admin.chat.index', compact('messages', 'keywords'));
    }
}
