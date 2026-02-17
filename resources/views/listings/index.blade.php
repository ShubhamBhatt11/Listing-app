<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Browse Listings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form action="{{ route('listings.index') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="q" class="block text-sm font-medium text-gray-700">Search</label>
                                <input type="text" id="q" name="q" value="{{ request('q') }}"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Search title or description">
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" id="city" name="city" value="{{ request('city') }}"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Filter by city">
                            </div>

                            <div>
                                <label for="sort" class="block text-sm font-medium text-gray-700">Sort</label>
                                <select id="sort" name="sort" class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Newest</option>
                                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                            Search
                        </button>
                    </form>
                </div>
            </div>

            <!-- Listings Grid -->
            @if ($listings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($listings as $listing)
                        <a href="{{ route('listings.show', $listing) }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="font-semibold text-lg text-gray-800 line-clamp-2 flex-1">
                                        {{ $listing->title }}
                                    </h3>
                                    <span class="ml-2 px-3 py-1 text-xs font-semibold rounded-full whitespace-nowrap
                        
                                    ">
                                        {{ ucfirst($listing->status) }}
                                    </span>
                                </div>

                                <p class="text-gray-600 text-sm mb-3 line-clamp-3">
                                    {{ $listing->description }}
                                </p>

                                <div class="mb-4 border-t pt-4">
                                    <p class="text-gray-600 text-sm">
                                        <strong>{{ $listing->city }}</strong>
                                    </p>
                                    <p class="text-2xl font-bold text-blue-600">
                                        ${{ number_format($listing->price_cents / 100, 2) }}
                                    </p>
                                </div>

                                <p class="text-xs text-gray-500">
                                    Posted by {{ $listing->user->name }} on {{ $listing->published_at?->format('M d, Y') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $listings->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-600">
                        <p>No listings found. Try adjusting your filters.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
