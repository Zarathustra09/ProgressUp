<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $request->input('user_id');

        $chats = Chat::with(['userOne', 'userTwo', 'messages'])
            ->where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->get();

        return response()->json($chats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_one_id' => 'required|exists:users,id',
            'user_two_id' => 'required|exists:users,id',
        ]);

        $chat = Chat::create($request->all());
        return response()->json($chat, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        return response()->json($chat->load(['userOne', 'userTwo', 'messages']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chat $chat)
    {
        $request->validate([
            'user_one_id' => 'required|exists:users,id',
            'user_two_id' => 'required|exists:users,id',
        ]);

        $chat->update($request->all());
        return response()->json($chat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        $chat->delete();
        return response()->json(null, 204);
    }
}
