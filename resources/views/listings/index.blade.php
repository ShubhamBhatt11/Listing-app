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
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="q" class="block text-sm font-medium text-gray-700">Search</label>
                                <input type="text" id="q" name="q" value="{{ request('q') }}"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition"
                                    placeholder="Search title or description">
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" id="city" name="city" value="{{ request('city') }}"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition"
                                    placeholder="Filter by city">
                            </div>

                            <div>
                                <label for="sort" class="block text-sm font-medium text-gray-700">Sort</label>
                                <select id="sort" name="sort" class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="">Newest</option>
                                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                                </select>
                            </div>
                        </div>

                        <!-- Loading indicator -->
                        <div id="loading" class="hidden text-sm text-blue-600 flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Updating results...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listings Grid -->
            <div data-listings-container class="transition-opacity duration-300">
                @if ($listings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($listings as $listing)
                        <a href="{{ route('listings.show', $listing) }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="font-semibold text-lg text-gray-800 line-clamp-2 flex-1">
                                        {{ $listing->title }}
                                    </h3>
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
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center text-gray-600">
                            <p>No listings found. Try adjusting your filters.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('q');
        const cityInput = document.getElementById('city');
        const sortSelect = document.getElementById('sort');
        const loadingIndicator = document.getElementById('loading');
        const container = document.querySelector('[data-listings-container]');
        let debounceTimer;

        function updateURL(params) {
            const url = new URL(window.location);
            Object.keys(params).forEach(key => {
                if (params[key]) {
                    url.searchParams.set(key, params[key]);
                } else {
                    url.searchParams.delete(key);
                }
            });
            window.history.replaceState({}, '', url);
        }

        function fetchListings() {
            const params = {
                q: searchInput.value,
                city: cityInput.value,
                sort: sortSelect.value
            };

            // Show loading state
            loadingIndicator.classList.remove('hidden');

            // Update URL
            updateURL(params);

            const query = new URLSearchParams(params).toString();

            fetch(`{{ route('listings.index') }}?${query}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector('[data-listings-container]');

                // Fade out, update, fade in
                container.style.opacity = '0.5';
                container.innerHTML = newContent.innerHTML;
                
                // Trigger fade in
                setTimeout(() => {
                    container.style.opacity = '1';
                }, 50);

                loadingIndicator.classList.add('hidden');
            })
            .catch(error => {
                console.error('Error fetching listings:', error);
                loadingIndicator.classList.add('hidden');
            });
        }

        // Debounced search input (300ms delay after user stops typing)
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchListings, 300);
        });

        // Immediate filter on city/sort change
        cityInput.addEventListener('change', fetchListings);
        sortSelect.addEventListener('change', fetchListings);

        // Smooth transition CSS
        container.style.transition = 'opacity 0.3s ease-in-out';
    </script>
</x-app-layout>
