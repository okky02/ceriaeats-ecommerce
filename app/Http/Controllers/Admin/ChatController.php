<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index()
    {
        $adminIds = User::where('role', 'admin')->pluck('id');

        $users = User::whereHas('sentMessages', function ($query) use ($adminIds) {
            $query->whereIn('receiver_id', $adminIds);
        })
        ->withCount([
            'sentMessages as unread_count' => function ($query) use ($adminIds) {
                $query->whereIn('receiver_id', $adminIds)
                    ->where('is_read', false);
            }
        ])
        ->with(['sentMessages' => function ($q) use ($adminIds) {
            $q->whereIn('receiver_id', $adminIds)->latest();
        }])
        ->get()
        ->sortByDesc(function ($user) {
            return optional($user->sentMessages->first())->created_at;
        })
        ->values();

        $users = $users->map(function ($user) {
            $lastMessage = $user->sentMessages->first()?->message ?? '-';
            $user->last_message = \Illuminate\Support\Str::limit($lastMessage, 30);
            return $user;
        });        

        return view('admin.chat-master', compact('users'));
    }

    public function fetchMessages($userId)
    {
        $adminIds = User::where('role', 'admin')->pluck('id');

        // Tandai semua pesan dari user ke admin sebagai sudah dibaca
        Message::where('sender_id', $userId)
            ->whereIn('receiver_id', $adminIds)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where(function ($query) use ($userId, $adminIds) {
                $query->whereIn('sender_id', $adminIds)
                    ->where('receiver_id', $userId);
            })->orWhere(function ($query) use ($userId, $adminIds) {
                $query->where('sender_id', $userId)
                    ->whereIn('receiver_id', $adminIds);
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'sender_name' => $msg->sender->name,
                    'message' => $msg->message,
                    'sender_id' => $msg->sender_id,
                    'sender_role' => $msg->sender->role,
                    'sender_profile_photo' => $msg->sender->profile_photo,
                    'created_at' => $msg->created_at->toDateTimeString(),
                ];
            });            

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'],
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['success' => true]);
    }

    public function userListPartial()
    {
        $adminIds = User::where('role', 'admin')->pluck('id');

        $users = User::whereHas('sentMessages', function ($query) use ($adminIds) {
            $query->whereIn('receiver_id', $adminIds);
        })
        ->withCount([
            'sentMessages as unread_count' => function ($query) use ($adminIds) {
                $query->whereIn('receiver_id', $adminIds)
                    ->where('is_read', false);
            }
        ])
        ->with(['sentMessages' => function ($q) use ($adminIds) {
            $q->whereIn('receiver_id', $adminIds)->latest();
        }])
        ->get()
        ->sortByDesc(function ($user) {
            return optional($user->sentMessages->first())->created_at;
        })
        ->values();

        $users = $users->map(function ($user) {
            $lastMessage = $user->sentMessages->first()?->message ?? '-';
            $user->last_message = Str::limit($lastMessage, 30);
            return $user;
        });

        return view('admin.chat.partials.user-list', compact('users'));
    }
}
