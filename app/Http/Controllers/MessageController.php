<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        DB::beginTransaction();

        try {
            $chat_id = $request->query('chat_id');

            if (!$chat_id) {
                DB::rollBack();
                return response()->json(['error' => 'Chat ID is required'], 400);
            }

            $messages = Message::where('chat_id', $chat_id)
                ->with([
                    'sender:id,first_name,last_name,email,profile_image',
                    'receiver:id,first_name,last_name,email,profile_image',
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            DB::commit();
            return response()->json($messages);
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

            DB::commit();
            return response()->json($message, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }

    public function show(Message $message)
    {
        DB::beginTransaction();

        try {
            $message = $message->load(['sender', 'receiver', 'chat']);

            DB::commit();
            return response()->json($message);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }

    public function update(Request $request, Message $message)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'seen' => 'boolean',
            ]);

            $message->update($request->all());

            DB::commit();
            return response()->json($message);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }

    public function destroy(Message $message)
    {
        DB::beginTransaction();

        try {
            $message->delete();

            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }

    public function getSpecificStudent(Request $request)
    {
        DB::beginTransaction();

        try {
            $userId = $request->query('user_id');
            $user = User::findOrFail($userId);
            $branchId = $user->branch_id;

            $search = $request->query('search');

            $students = User::where('role_id', 1)
                ->where('branch_id', $branchId)
                ->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                })
                ->select('id', 'first_name', 'last_name', 'email', 'profile_image')
                ->paginate(10);

            DB::commit();
            return response()->json($students);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }
}
