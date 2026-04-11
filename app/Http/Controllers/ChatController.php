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
        $users = User::where('id', '!=', Auth::id())->get();
        // If no user is provided, default to the first available contact
        $receiver = $user && $user->id ? $user : $users->first();
        
        $messages = [];
        if ($receiver) {
            $messages = ChatMessage::where(function($q) use ($receiver) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $receiver->id);
            })->orWhere(function($q) use ($receiver) {
                $q->where('sender_id', $receiver->id)->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'asc')->get();

            // Mark as read
            ChatMessage::where('sender_id', $receiver->id)
                      ->where('receiver_id', Auth::id())
                      ->where('is_read', false)
                      ->update(['is_read' => true]);
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
