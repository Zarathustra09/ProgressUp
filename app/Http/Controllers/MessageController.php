<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        return Message::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'body' => 'nullable|string|max:5000',
            'attachment' => 'nullable|string',
        ]);

        $message = Message::create($request->all());

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
