<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GutendexService
{
    protected $baseUrl = 'https://gutendex.com/books';

    /**
     * Fetch a list of books.
     * Can be filtered by search term.
     */
    public function getBooks(int $page = 1, ?string $search = null)
    {
        $query = ['page' => $page];
        if ($search) {
            $query['search'] = $search;
        }

        $response = Http::get($this->baseUrl, $query);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    /**
     * Fetch a single book by its ID.
     */
    public function getBookById(int $id)
    {
        $response = Http::get("{$this->baseUrl}/{$id}");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
