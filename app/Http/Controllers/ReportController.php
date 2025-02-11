<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Attendance;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $rooms = Room::withCount(['students', 'roomStaff as staff_count', 'users as parents_count' => function ($query) {
            $query->where('role_id', 0); // Assuming role_id 2 is for parents
        }])->get();

        $branchAttendanceRates = $this->calculateAttendanceRates();

        return view('reports.index', compact('rooms', 'branchAttendanceRates'));
    }

    private function calculateAttendanceRates()
    {
        $branches = Room::with(['users' => function ($query) {
            $query->where('role_id', 1); // Assuming role_id 1 is for students
        }])->get();

        return $branches->map(function ($branch) {
            $totalScheduledDays = 0;
            $totalPresentDays = 0;
            $totalAbsentDays = 0;
            $totalLateDays = 0;

            foreach ($branch->users as $student) {
                $attendances = Attendance::where('student_id', $student->id)->get();
                $totalScheduledDays += $attendances->count();
                $totalPresentDays += $attendances->where('status', 'present')->count();
                $totalAbsentDays += $attendances->where('status', 'absent')->count();
                $totalLateDays += $attendances->where('status', 'late')->count();
            }

            $presentRate = $totalScheduledDays > 0 ? ($totalPresentDays / $totalScheduledDays) * 100 : 0;
            $absentRate = $totalScheduledDays > 0 ? ($totalAbsentDays / $totalScheduledDays) * 100 : 0;
            $lateRate = $totalScheduledDays > 0 ? ($totalLateDays / $totalScheduledDays) * 100 : 0;

            return [
                'branch' => $branch->name,
                'present_rate' => $presentRate,
                'absent_rate' => $absentRate,
                'late_rate' => $lateRate
            ];
        });
    }
}
