<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->query('user_id');

        if (!$user_id) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        $messages = Message::where('receiver_id', $user_id)->get();

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'body' => 'nullable|string|max:5000',
            'attachment' => 'nullable|image', // Validate that the attachment is an image
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
        return $message;
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
}
