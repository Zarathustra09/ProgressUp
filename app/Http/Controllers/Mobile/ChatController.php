<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Chat;
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

            $chats = Chat::with([
                'userOne:id,first_name,last_name,email,profile_image,created_at',
                'userTwo:id,first_name,last_name,email,profile_image,created_at',
                'messages' => function ($query) {
                    $query->latest()->first();
                }
            ])
                ->where('user_one_id', $userId)
                ->orWhere('user_two_id', $userId)
                ->get();

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
