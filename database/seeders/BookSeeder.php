<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\GutendexService;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    protected $gutendexService;

    public function __construct(GutendexService $gutendexService)
    {
        $this->gutendexService = $gutendexService;
    }

    public function run(): void
    {
        $this->command->info("Fetching books from Gutendex API...");

        // Fetch the first 2 pages (approx 64 books)
        $bookDataPage1 = $this->gutendexService->getBooks(1);
        $bookDataPage2 = $this->gutendexService->getBooks(2);

        if (!$bookDataPage1 || !$bookDataPage2) {
            $this->command->error("Failed to fetch books from the API.");
            return;
        }

        $allBooks = array_merge($bookDataPage1['results'], $bookDataPage2['results']);
        $categories = Category::all();

        foreach ($allBooks as $bookItem) {
            // Skip books with no clear text format
            if (!isset($bookItem['formats']['text/plain; charset=us-ascii'])) {
                continue;
            }

            Book::updateOrCreate(
                ['id' => $bookItem['id']], // Match by ID
                [
                    'title' => $bookItem['title'],
                    'author' => $bookItem['authors'][0]['name'] ?? 'Unknown',
                    'description' => "A classic work by " . ($bookItem['authors'][0]['name'] ?? 'Unknown'),
                    'cover_image_url' => $bookItem['formats']['image/jpeg'] ?? null,
                    'text_url' => $bookItem['formats']['text/plain; charset=us-ascii'] ?? null,
                    'category_id' => $categories->random()->id // Assign a random category for now
                ]
            );
        }

        $this->command->info("Successfully seeded books from Gutendex.");
    }
}
