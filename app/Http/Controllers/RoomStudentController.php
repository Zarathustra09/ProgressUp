<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomStudent;
use App\Models\User;
use Illuminate\Http\Request;

class RoomStudentController extends Controller
{
    public function show($id)
    {
        $room = Room::with('students')->findOrFail($id);
        return view('room.show', compact('room'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'student_id' => 'required|exists:users,id',
        ]);

        $room = Room::findOrFail($request->room_id);
        $student = User::findOrFail($request->student_id);

        if ($student->role_id != 1) {
            return response()->json(['error' => 'The user is not a student.'], 400);
        }

        $roomStudent = new RoomStudent();
        $roomStudent->room_id = $room->id;
        $roomStudent->student_id = $student->id;
        $roomStudent->save();

        return response()->json(['success' => 'Student added to the room successfully.']);
    }

    public function destroy($student_id)
    {
        $roomStudent = RoomStudent::where('student_id', $student_id)->firstOrFail();
        $roomStudent->delete();

        return response()->json(['success' => 'Student removed from the room successfully.']);
    }
}
