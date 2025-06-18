<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        // Simple search functionality
        $query = Book::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('author', 'like', '%' . $request->search . '%');
        }

        $books = $query->orderBy('title')->paginate(25);

        return view('books.index', compact('books'));
    }

// In BookController.php
    public function show(Book $book)
    {
        // Eager load the reviews and the user associated with each review
        $book->load('reviews.user');
        return view('books.show', compact('book'));
    }
}
