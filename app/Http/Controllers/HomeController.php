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
        $response = Http::withOptions(['verify' => false])->get('https://api.quotable.io/random');
        return $response->json();
    }
}
