<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(User $user = null)
    {
        // 1. Identify the receiver first
        if (!$user || !$user->id) {
            $firstUser = User::where('id', '!=', Auth::id())
                ->addSelect(['latest_message_at' => ChatMessage::select('created_at')
                    ->where(function($q) {
                        $q->whereColumn('sender_id', 'users.id')->where('receiver_id', Auth::id());
                    })->orWhere(function($q) {
                        $q->whereColumn('receiver_id', 'users.id')->where('sender_id', Auth::id());
                    })->latest()->take(1)
                ])
                ->orderByDesc('latest_message_at')
                ->first();
            $receiver = $firstUser;
        } else {
            $receiver = $user;
        }

        // 2. Mark as read immediately if we have a receiver
        if ($receiver) {
            ChatMessage::where('sender_id', $receiver->id)
                      ->where('receiver_id', Auth::id())
                      ->where('is_read', false)
                      ->update(['is_read' => true]);
        }

        // 3. Fetch users with updated unread counts
        $users = User::where('id', '!=', Auth::id())
            ->withCount(['sentMessages as unread_count' => function($query) {
                $query->where('receiver_id', Auth::id())->where('is_read', false);
            }])
            ->addSelect(['latest_message_at' => ChatMessage::select('created_at')
                ->where(function($q) {
                    $q->whereColumn('sender_id', 'users.id')->where('receiver_id', Auth::id());
                })->orWhere(function($q) {
                    $q->whereColumn('receiver_id', 'users.id')->where('sender_id', Auth::id());
                })->latest()->take(1)
            ])
            ->orderByDesc('unread_count')
            ->orderByDesc('latest_message_at')
            ->get();

        $messages = [];
        if ($receiver) {
            $messages = ChatMessage::where(function($q) use ($receiver) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $receiver->id);
            })->orWhere(function($q) use ($receiver) {
                $q->where('sender_id', $receiver->id)->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'asc')->get();
        }

        return view('chat.index', compact('users', 'receiver', 'messages'));
    }

    public function send(Request $request)
    {
        try {
            $request->validate([
                'receiver_id' => 'required',
                'message' => 'nullable|string',
                'image' => 'nullable|image|max:10240', // 10MB max for high-fidelity sharing
            ]);

            $imagePath = null;
            $type = 'text';

            if ($request->hasFile('image')) {
                $ik = app(\App\Services\ImageKitService::class);
                $upload = $ik->upload($request->file('image'), 'chat_' . Auth::id() . '_' . time(), 'chat');
                if ($upload && isset($upload->filePath)) {
                    $imagePath = $upload->filePath;
                    $type = 'image';
                }
            }

            $msg = ChatMessage::create([
                'sender_id' => Auth::id(),
                'receiver_id' => intval($request->receiver_id),
                'message' => $request->message ?? '',
                'type' => $type,
                'image_path' => $imagePath,
            ]);

            return response()->json([
                'status' => 'success', 
                'message' => $msg,
                'image_url' => $msg->image_url
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Chat Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fetch(User $user)
    {
        $messages = ChatMessage::where(function($q) use ($user) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
        })->orWhere(function($q) use ($user) {
            $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        // Mark as read
        ChatMessage::where('sender_id', $user->id)
                  ->where('receiver_id', Auth::id())
                  ->where('is_read', false)
                  ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function deleteMessage(Request $request, $id)
    {
        $message = ChatMessage::findOrFail($id);

        // Security: only sender can delete their own message
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // If message had an image, delete it from ImageKit too
        if ($message->image_path) {
            try {
                $ik = app(\App\Services\ImageKitService::class);
                $ik->deleteFile($message->image_path);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('ImageKit delete failed: ' . $e->getMessage());
            }
        }

        $message->delete();

        return response()->json(['status' => 'deleted']);
    }
}
