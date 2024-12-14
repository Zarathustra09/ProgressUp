<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $parentCount = $this->countParents();
        $studentCount = $this->countStudent();
        $roomCount = $this->countRoom();

        // Fetch quotes from the new API
        $quote = $this->fetchQuotes();
        $quoteContent = $quote['content'];
        $quoteAuthor = $quote['author'];

        return view('home', compact('parentCount', 'studentCount', 'roomCount', 'quoteContent', 'quoteAuthor'));
    }

    public function countParents()
    {
        return count(User::where('role_id', '0')->get());
    }

    public function countStudent()
    {
        return count(User::where('role_id', '1')->get());
    }

    public function countRoom()
    {
        return count(Room::all());
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
}
