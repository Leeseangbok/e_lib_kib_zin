<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // To allow mass assignment of the book ID from the API
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * The users that have this book as a favorite.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'book_user');
    }
}
