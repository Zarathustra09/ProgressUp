<?php

namespace App\Http\Controllers;

use App\Models\StudentSchedule;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentScheduleController extends Controller
{
    public function index()
    {
        $schedules = StudentSchedule::with(['student', 'room'])->get();
        return view('student_schedule.index', compact('schedules'));
    }

    public function create(Request $request)
    {
        Log::info('Create function called with request data:', $request->all());

        $roomId = $request->query('room_id');
        $studentId = $request->query('student_id');

        return view('studentSchedule.create', compact('roomId', 'studentId'));
    }

    public function show($id)
    {
        Log::info('Show function called with id:', ['id' => $id]);

        $student = User::where('role_id', 1)->with(['studentSchedules.room'])->find($id);

        if (!$student) {
            Log::error('Student not found with id:', ['id' => $id]);
            abort(404, 'Student not found');
        }

        foreach ($student->studentSchedules as $schedule) {
            $schedule->qr_code_url = route('attendances.store', [
                'student_id' => $student->id,
                'schedule_id' => $schedule->id,
                'date' => now()->toDateString(),
                'status' => 'present'
            ]);
        }

        Log::info('Show function found student:', ['student' => $student]);

        return view('studentSchedule.show', compact('student'));
    }

    public function store(Request $request)
    {
        Log::info('Store function called with request data:', $request->all());

        $request->validate([
            'student_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'event_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255|regex:/^(\b\w+\b[\s\r\n]*){1,50}$/',
            'session' => 'required|integer|min:1',
        ]);

        try {
            $schedule = StudentSchedule::create($request->all());
            Log::info('Student schedule created successfully:', $schedule->toArray());
        } catch (\Exception $e) {
            Log::error('Error creating student schedule:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'There was an error creating the student schedule.');
        }

        return redirect()->route('studentSchedules.show', $schedule->student_id)->with('success', 'Student schedule created successfully.');
    }

    public function update(Request $request, $id)
    {
        Log::info('Update function called with request data:', $request->all());

        $validator = \Validator::make($request->all(), [
            'student_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'event_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'session' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            Log::error('Validation errors:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $schedule = StudentSchedule::findOrFail($id);
            $schedule->update($request->all());
            Log::info('Student schedule updated successfully:', $schedule->toArray());
        } catch (\Exception $e) {
            Log::error('Error updating student schedule:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'There was an error updating the student schedule.');
        }

        return redirect()->route('studentSchedules.show', $schedule->student_id)->with('success', 'Student schedule updated successfully.');
    }

    public function destroy($id)
    {
        $schedule = StudentSchedule::findOrFail($id);
        $studentId = $schedule->student_id;

        // Validate that this is not the last remaining schedule
        if (StudentSchedule::where('student_id', $studentId)->count() <= 1) {
            return back()->with('error', 'Cannot delete the last remaining student schedule.');
        }

        // Delete related attendances
        $schedule->attendances()->delete();

        // Delete the schedule
        $schedule->delete();

        return redirect()->route('studentSchedules.show', $studentId)->with('success', 'Student schedule deleted successfully.');
    }

    public function showSingle($id)
    {
        $schedule = StudentSchedule::findOrFail($id);

        if (!$schedule) {
            Log::error('Student schedule not found with id:', ['id' => $id]);
            abort(404, 'Student schedule not found');
        }

        return response()->json(['schedule' => $schedule]);
    }
}
