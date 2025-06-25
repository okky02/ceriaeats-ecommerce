<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function index()
    {
        return view('user.contact-master');
    }

    public function fetchMessages()
{
    $userId = Auth::id();
    $adminIds = User::where('role', 'admin')->pluck('id');

    $messages = Message::where(function ($query) use ($userId, $adminIds) {
            $query->whereIn('sender_id', $adminIds)
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId, $adminIds) {
            $query->where('sender_id', $userId)
                ->whereIn('receiver_id', $adminIds);
        })
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function ($msg) use ($userId) {
            return [
                'sender_name' => $msg->sender_id === $userId ? 'You' : 'Admin',
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
        ]);

        // Kirim ke semua admin
        $admin = User::where('role', 'admin')->first(); 

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $admin->id,
            'message' => $validated['message'],
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['success' => true]);
    }
}

