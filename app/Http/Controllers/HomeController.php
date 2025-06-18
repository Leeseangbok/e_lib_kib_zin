<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category; // Add this
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Define the category slugs we want to feature
        $featuredSlugs = ['horror', 'mystery', 'fantasy', 'romance'];

        // Eager load the categories to avoid multiple queries
        $featuredCategories = Category::whereIn('slug', $featuredSlugs)
                                        ->with(['books' => function ($query) {
                                            // For each category, get the latest 10 books
                                            $query->latest()->take(10);
                                        }])
                                        ->get()
                                        ->keyBy('slug'); // Key by slug for easy access in the view

        // Prepare the collections for the view
        $collections = [
            'Latest' => Book::latest()->take(10)->get(),
            'Popular' => Book::inRandomOrder()->take(10)->get(), // Using random for now to simulate popularity
        ];

        // Add the category collections
        foreach ($featuredSlugs as $slug) {
            if (isset($featuredCategories[$slug])) {
                $category = $featuredCategories[$slug];
                $collections[$category->name] = $category->books;
            }
        }

        return view('welcome', compact('collections'));
    }
}
