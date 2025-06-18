<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The books that the user has marked as favorites (in their library).
     */
    public function favoriteBooks()
    {
        return $this->belongsToMany(Book::class, 'book_user');
    }

    /**
     * The reviews that the user has written.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
