<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\LibraryController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Book and Category Routes (Publicly Accessible)
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

     Route::get('/my-library', [LibraryController::class, 'index'])->name('library.index');
    Route::post('/library/add/{book}', [LibraryController::class, 'add'])->name('library.add');
    Route::delete('/library/remove/{book}', [LibraryController::class, 'remove'])->name('library.remove');

    Route::post('/reviews/{book}', [ReviewController::class, 'store'])->name('reviews.store');
});

require __DIR__ . '/auth.php';
