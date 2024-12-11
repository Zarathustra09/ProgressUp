<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoomScheduleController extends Controller
{
    public function index()
    {
        $schedules = RoomSchedule::with('room')->get();
        return view('schedule.index', compact('schedules'));
    }
    public function create(Request $request)
    {
        $roomId = $request->query('room_id');
        return view('schedule.create', compact('roomId'));
    }

    public function store(Request $request)
    {
        Log::info('Store function called with request data:', $request->all());

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'event_name' => 'required|string|max:255',
        ]);

        try {
            $schedule = RoomSchedule::create($request->all());
            Log::info('Room schedule created successfully:', $schedule->toArray());
        } catch (\Exception $e) {
            Log::error('Error creating room schedule:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'There was an error creating the room schedule.');
        }

        return redirect()->route('room_schedules.show', $schedule->id)->with('success', 'Room schedule created successfully.');
    }

    public function show($id)
    {
        $schedule = RoomSchedule::with('room')->findOrFail($id);
        $room = $schedule->room;
        return view('schedule.show', compact('schedule', 'room'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'event_name' => 'required|string|max:255',
        ]);

        $schedule = RoomSchedule::findOrFail($id);
        $schedule->update($request->all());

        return redirect()->route('room_schedules.show', $schedule->id)->with('success', 'Room schedule updated successfully.');
    }


    public function destroy($id)
    {
        $schedule = RoomSchedule::findOrFail($id);
        $roomId = $schedule->room_id;

        // Validate that this is not the last remaining schedule
        if (RoomSchedule::where('room_id', $roomId)->count() <= 1) {
            return back()->with('error', 'Cannot delete the last remaining room schedule.');
        }

        $schedule->delete();

        // Find the first schedule of the room
        $firstSchedule = RoomSchedule::where('room_id', $roomId)->first();

        if ($firstSchedule) {
            return redirect()->route('room_schedules.show', $firstSchedule->id)->with('success', 'Room schedule deleted successfully.');
        } else {
            return redirect()->route('room_schedules.index')->with('success', 'Room schedule deleted successfully.');
        }
    }
}
