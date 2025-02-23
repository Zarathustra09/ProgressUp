<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::withCount('students')->get();
        return view('room.index', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'location' => 'required|string|max:255',
        ]);

        Room::create($request->all());

        return response()->json(['success' => 'Room created successfully.']);
    }

    public function show($id)
    {
        $room = Room::findOrFail($id);
        return response()->json($room);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'location' => 'required|string|max:255',
        ]);

        $room = Room::findOrFail($id);
        $room->update($request->all());

        return response()->json(['success' => 'Room updated successfully.']);
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $room = Room::findOrFail($id);

            // Log the room to be deleted
            Log::info('Deleting room:', ['room' => $room]);

            // Delete related student schedules and their attendances
            foreach ($room->studentSchedules as $schedule) {
                Log::info('Deleting student schedule:', ['schedule' => $schedule]);
                $schedule->attendances()->delete();
                $schedule->delete();
            }

            // Delete related attendances for users
            foreach ($room->users as $user) {
                Log::info('Deleting attendances for user:', ['user' => $user]);
                $user->attendances()->delete();
            }

            // Delete related users
            foreach ($room->users as $user) {
                Log::info('Deleting user:', ['user' => $user]);
                $user->delete();
            }

            // Delete related room staff
            foreach ($room->roomStaff as $staff) {
                Log::info('Deleting room staff:', ['staff' => $staff]);
                $staff->delete();
            }

            // Delete the room
            $room->delete();

            DB::commit();

            return response()->json(['success' => 'Room and related records deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete room:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to delete room.'], 500);
        }
    }

    public function list()
    {
        Log::info(Room::all());
        return Room::all();
    }
}
