<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminMessage extends Controller
{
    public function index(Request $request)
    {
        DB::beginTransaction();

        try {
            $search = $request->input('search');

            // Step 1: Get all chat IDs where user_one_id or user_two_id has role_id 3
            $chatQuery = Chat::query()
                ->whereHas('userOne', function ($query) {
                    $query->where('role_id', 3);
                })
                ->orWhereHas('userTwo', function ($query) {
                    $query->where('role_id', 3);
                });

            if ($search) {
                $chatQuery->where(function ($query) use ($search) {
                    $query->whereHas('userOne', function ($query) use ($search) {
                        $query->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%');
                    })->orWhereHas('userTwo', function ($query) use ($search) {
                        $query->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%');
                    });
                });
            }

            $chatIds = $chatQuery->pluck('id');

            // Step 2: Get the latest message for each chat ID
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
            return view('message.index', compact('chats'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }


    public function show($id)
    {
        DB::beginTransaction();

        try {
            // Fetch the chat details with user information and messages
            $chat = Chat::with([
                'userOne:id,first_name,last_name,email,profile_image,created_at',
                'userTwo:id,first_name,last_name,email,profile_image,created_at',
                'messages.sender:id,first_name,last_name,email,profile_image',
                'messages.receiver:id,first_name,last_name,email,profile_image'
            ])->findOrFail($id);

            DB::commit();
            return view('message.show', compact('chat'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }


    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            Log::info('Message request', $request->all());
            $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'chat_id' => 'required|exists:chats,id',
                'body' => 'nullable|string|max:5000',
                'attachment' => 'nullable|mimes:jpg,png|max:2048',
            ]);

            // Fetch the chat details
            $chat = Chat::findOrFail($request->input('chat_id'));

            // Determine which user has role_id of 3
            $sender = User::where('role_id', 3)
                ->whereIn('id', [$chat->user_one_id, $chat->user_two_id])
                ->first();

            if (!$sender) {
                DB::rollBack();
                return response()->json(['error' => 'You do not have permission to interfere with this chat'], 403);
            }

            $data = $request->all();
            $data['sender_id'] = $sender->id;

            if ($request->hasFile('attachment')) {
                $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
            }

            $message = Message::create($data);

            DB::commit();
            return response()->json($message, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed', ['exception' => $e]);
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }

    public function fetchMessages($chatId)
    {
        try {
            $chat = Chat::with([
                'messages.sender:id,first_name,last_name,email,profile_image',
                'messages.receiver:id,first_name,last_name,email,profile_image'
            ])->findOrFail($chatId);

            return response()->json($chat->messages, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch messages'], 500);
        }
    }
}
