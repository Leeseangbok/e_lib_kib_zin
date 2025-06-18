<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $book->title }}
        </h2>
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="md:w-1/3">
                            <img class="w-full rounded-lg shadow-md"
                                src="{{ $book->cover_image_url ?? 'https://via.placeholder.com/400x600.png?text=No+Cover' }}"
                                alt="Cover of {{ $book->title }}">
                        </div>

                        <div class="md:w-2/3">
                            <h1 class="text-4xl font-bold">{{ $book->title }}</h1>
                            <p class="text-xl text-gray-700 mt-2">by {{ $book->author }}</p>

                            @if ($book->category)
                                <p class="text-md text-gray-600 mt-1">Category: <a
                                        href="{{ route('categories.show', $book->category->slug) }}"
                                        class="text-indigo-600 hover:underline">{{ $book->category->name }}</a></p>
                            @endif

                            <div class="mt-6">
                                <a href="{{ $book->text_url }}" target="_blank"
                                    class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700 focus:ring-green-500 active:bg-green-700 disabled:opacity-25 transition">
                                    Read Book
                                </a>
                                @auth
                                    @if(Auth::user()->favoriteBooks->contains($book))
                                        {{-- User has this book in their library, show Remove button --}}
                                        <form action="{{ route('library.remove', $book) }}" method="POST" class="inline-block ml-4">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-red-500">
                                                Remove from Library
                                            </button>
                                        </form>
                                    @else
                                        {{-- User does not have this book, show Add button --}}
                                        <form action="{{ route('library.add', $book) }}" method="POST" class="inline-block ml-4">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-blue-500">
                                                Add to Library
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>

                            <div class="mt-8">
                                <h3 class="text-2xl font-semibold border-b pb-2">Description</h3>
                                <p class="mt-4 text-gray-700 leading-relaxed">{{ $book->description }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12">
                        <h3 class="text-2xl font-semibold border-b pb-2">Reviews & Ratings</h3>

                        @auth
                            <div class="mt-6 bg-gray-50 p-6 rounded-lg">
                                <h4 class="font-bold text-lg">Leave a Review</h4>
                                <form action="{{ route('reviews.store', $book) }}" method="POST" class="mt-4">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                                        <select name="rating" id="rating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="5">★★★★★</option>
                                            <option value="4">★★★★☆</option>
                                            <option value="3">★★★☆☆</option>
                                            <option value="2">★★☆☆☆</option>
                                            <option value="1">★☆☆☆☆</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="content" class="block text-sm font-medium text-gray-700">Review</label>
                                        <textarea name="content" id="content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="What did you think of this book?"></textarea>
                                    </div>
                                    <div>
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">Submit Review</button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <p class="mt-4 text-gray-600"><a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Log in</a> to leave a review.</p>
                        @endauth

                        <div class="mt-8 space-y-6">
                            @forelse($book->reviews as $review)
                                <div class="bg-white p-4 border rounded-lg shadow-sm">
                                    <div class="flex items-center mb-2">
                                        <div class="font-bold text-gray-800">{{ $review->user->name }}</div>
                                        <div class="ml-4 text-yellow-500 flex">
                                            @for ($i = 0; $i < 5; $i++)
                                                @if ($i < $review->rating)
                                                    <span>★</span> @else
                                                    <span class="text-gray-300">★</span> @endif
                                            @endfor
                                        </div>
                                        <div class="ml-auto text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</div>
                                    </div>
                                    <p class="text-gray-700">{{ $review->content }}</p>
                                </div>
                            @empty
                                <p class="mt-4 text-gray-600">This book has no reviews yet.</p>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
