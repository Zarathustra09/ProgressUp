<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $chat_id = $request->query('chat_id');

        if (!$chat_id) {
            return response()->json(['error' => 'Chat ID is required'], 400);
        }

        $messages = Message::where('chat_id', $chat_id)
            ->with([
                'sender:id,first_name,last_name,email,profile_image',
                'receiver:id,first_name,last_name,email,profile_image',
                'chat'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'chat_id' => 'required|exists:chats,id',
            'body' => 'nullable|string|max:5000',
            'attachment' => 'nullable|image',
        ]);

        $data = $request->all();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments');
        }

        $message = Message::create($data);

        return response()->json($message, 201);
    }

    public function show(Message $message)
    {
        return response()->json($message->load(['sender', 'receiver', 'chat']));
    }

    public function update(Request $request, Message $message)
    {
        $request->validate([
            'seen' => 'boolean',
        ]);

        $message->update($request->all());

        return response()->json($message);
    }

    public function destroy(Message $message)
    {
        $message->delete();

        return response()->json(null, 204);
    }

    public function getAllStudents(Request $request)
    {
        $students = User::where('role_id', 1)
            ->select('id', 'first_name', 'last_name', 'email', 'profile_image')
            ->paginate(10);
        return response()->json($students);
    }
}
