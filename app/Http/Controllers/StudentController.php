<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('users.role_id', 1)
            ->join('student_school_details', 'users.id', '=', 'student_school_details.user_id')
            ->join('users as parents', 'users.parent_id', '=', 'parents.id')
            ->select('users.*', 'student_school_details.student_id', 'parents.first_name as parent_first_name', 'parents.last_name as parent_last_name');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('users.first_name', 'like', "%{$search}%")
                    ->orWhere('users.last_name', 'like', "%{$search}%")
                    ->orWhere('student_school_details.student_id', 'like', "%{$search}%")
                    ->orWhere('parents.first_name', 'like', "%{$search}%")
                    ->orWhere('parents.last_name', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(9);

        return view('student.index', compact('students'));
    }
}
