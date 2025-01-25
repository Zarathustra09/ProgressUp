<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\StudentSchedule;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{

    public function index($branch_id)
    {
        $users = User::where('branch_id', $branch_id)
            ->where('role_id', 1)
            ->select('id','first_name', 'last_name', 'email', 'parent_id', 'profile_image')
            ->with('parent:id,first_name,last_name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                    'parent' => $user->parent ? $user->parent->first_name . ' ' . $user->parent->last_name : null,
                    'profile_image' => $user->profile_image,
                ];
            });
        return response()->json($users);
    }

    public function showSchedule($user_id)
    {
        $schedules = StudentSchedule::where('student_id', $user_id)->get();
        return response()->json($schedules);
    }

    public function showAttendance($schedule_id)
    {
        $attendances = Attendance::where('schedule_id', $schedule_id)->get();
        return response()->json($attendances);
    }
}
