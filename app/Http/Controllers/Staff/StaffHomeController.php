<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StaffHomeController extends Controller
{
    public function index()
    {
        $parentCount = $this->countParents();
        $studentCount = $this->countStudent();
        $staffCount = $this->countStaff();
        $quote = $this->fetchQuotes();
        $allStudents = $this->getStudentsWithCompletedSchedules();
        $quoteContent = $quote['content'];
        $quoteAuthor = $quote['author'];


        return view('staffPages.home', compact('parentCount', 'studentCount', 'staffCount','quoteContent', 'quoteAuthor', 'allStudents'));
    }

    public function countParents()
    {
        $branchId = auth()->user()->branch_id;
        return count(User::where('role_id', '0')->where('branch_id', $branchId)->get());
    }

    public function countStaff()
    {
        $branchId = auth()->user()->branch_id;
        return count(User::where('role_id', '3')->where('branch_id', $branchId)->get());
    }

    public function countStudent()
    {
        $branchId = auth()->user()->branch_id;
        return count(User::where('role_id', '1')->where('branch_id', $branchId)->get());
    }


    public function fetchQuotes()
    {
        $quotes = [
            ['content' => 'The only limit to our realization of tomorrow is our doubts of today.', 'author' => 'Franklin D. Roosevelt'],
            ['content' => 'The purpose of our lives is to be happy.', 'author' => 'Dalai Lama'],
            ['content' => 'Life is what happens when you’re busy making other plans.', 'author' => 'John Lennon'],
            ['content' => 'Get busy living or get busy dying.', 'author' => 'Stephen King'],
            ['content' => 'You have within you right now, everything you need to deal with whatever the world can throw at you.', 'author' => 'Brian Tracy'],
            ['content' => 'Believe you can and you’re halfway there.', 'author' => 'Theodore Roosevelt'],
            ['content' => 'The only impossible journey is the one you never begin.', 'author' => 'Tony Robbins'],
            ['content' => 'Life is short, and it is up to you to make it sweet.', 'author' => 'Sarah Louise Delany'],
            ['content' => 'The unexamined life is not worth living.', 'author' => 'Socrates'],
            ['content' => 'Turn your wounds into wisdom.', 'author' => 'Oprah Winfrey'],
        ];

        // Randomly select a quote
        $quote = $quotes[array_rand($quotes)];

        return $quote;
    }

    public function getStudentsWithCompletedSchedules()
    {
        $branchId = auth()->user()->branch_id;
        $students = User::where('role_id', 1)
            ->where('branch_id', $branchId)
            ->with(['studentSchedules.attendances', 'studentSchedules.studentReports'])
            ->get();

        foreach ($students as $student) {
            foreach ($student->studentSchedules as $schedule) {
                $schedule->attended_sessions = $schedule->attendances->count();
                $schedule->report_count = $schedule->reportCount();
            }
        }

        $result = $students->filter(function ($student) {
            return $student->studentSchedules->filter(function ($schedule) {
                return $schedule->report_count != 1;
            })->isNotEmpty();
        });

        return $result;
    }
}
