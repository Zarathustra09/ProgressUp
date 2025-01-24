<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        DB::beginTransaction();

        try {
            $userId = $request->input('user_id');

            // Step 1: Get all chat IDs where the user is user_one
            $userOneChatIds = Chat::where('user_one_id', $userId)->pluck('id');

            // Step 2: Get all chat IDs where the user is user_two
            $userTwoChatIds = Chat::where('user_two_id', $userId)->pluck('id');

            // Step 3: Merge the chat IDs and remove duplicates
            $chatIds = $userOneChatIds->merge($userTwoChatIds)->unique();

            // Step 4: Get the latest message for each unique chat ID
            $latestMessages = DB::table('messages')
                ->whereIn('chat_id', $chatIds)
                ->select('chat_id', DB::raw('MAX(created_at) as latest_message_time'))
                ->groupBy('chat_id')
                ->get();

            // Fetch the latest message details
            $messages = Message::whereIn('chat_id', $chatIds)
                ->whereIn('created_at', $latestMessages->pluck('latest_message_time'))
                ->with(['sender:id,first_name,last_name,email,profile_image', 'receiver:id,first_name,last_name,email,profile_image'])
                ->get()
                ->groupBy('chat_id');

            // Fetch the chat details with user information
            $chats = Chat::with([
                'userOne:id,first_name,last_name,email,profile_image,created_at',
                'userTwo:id,first_name,last_name,email,profile_image,created_at'
            ])
                ->whereIn('id', $chatIds)
                ->get()
                ->map(function ($chat) use ($messages) {
                    $chat->messages = $messages->get($chat->id) ?? [];
                    return $chat;
                });

            DB::commit();
            return response()->json($chats);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'user_one_id' => 'required|exists:users,id',
                'user_two_id' => 'required|exists:users,id',
            ]);

            $chat = Chat::create($request->all());

            DB::commit();
            return response()->json($chat, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }

    public function show(Chat $chat)
    {
        DB::beginTransaction();

        try {
            $chat = $chat->load(['userOne', 'userTwo', 'messages']);

            DB::commit();
            return response()->json($chat);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }

    public function update(Request $request, Chat $chat)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'user_one_id' => 'required|exists:users,id',
                'user_two_id' => 'required|exists:users,id',
            ]);

            $chat->update($request->all());

            DB::commit();
            return response()->json($chat);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }

    public function destroy(Chat $chat)
    {
        DB::beginTransaction();

        try {
            $chat->delete();

            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }
}
