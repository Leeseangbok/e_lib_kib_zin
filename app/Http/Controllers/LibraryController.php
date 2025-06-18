<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    // Display the user's library
    public function index()
    {
        $user = Auth::user();
        $favoriteBooks = $user->favoriteBooks()->paginate(12);

        return view('library.index', compact('favoriteBooks'));
    }

    // Add a book to the library
    public function add(Book $book)
    {
        $user = Auth::user();
        $user->favoriteBooks()->syncWithoutDetaching([$book->id]); // Use syncWithoutDetaching to avoid duplicates

        return back()->with('success', 'Book added to your library!');
    }

    // Remove a book from the library
    public function remove(Book $book)
    {
        $user = Auth::user();
        $user->favoriteBooks()->detach($book->id);

        return back()->with('success', 'Book removed from your library.');
    }
}
