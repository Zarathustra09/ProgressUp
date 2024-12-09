<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
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
        $room = Room::findOrFail($id);
        $room->delete();

        return response()->json(['success' => 'Room deleted successfully.']);
    }

    public function list()
    {
        Log::info(Room::all());
        return Room::all();
    }
}
