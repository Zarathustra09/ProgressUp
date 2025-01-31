<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $rooms = Room::withCount(['students', 'roomStaff as staff_count', 'users as parents_count' => function ($query) {
            $query->where('role_id', 0); // Assuming role_id 2 is for parents
        }])->get();

        return view('reports.index', compact('rooms'));
    }
}
