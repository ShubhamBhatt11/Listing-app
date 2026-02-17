<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $listing->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <p class="text-gray-600 mb-2">
                            <strong>Location:</strong> {{ $listing->city }}
                        </p>
                        <p class="text-3xl font-bold text-blue-600 mb-2">
                            ${{ number_format($listing->price_cents / 100, 2) }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Posted on {{ $listing->published_at->format('M d, Y') }} by {{ $listing->user->name }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Description</h3>
                        <div class="prose max-w-none text-gray-700 whitespace-pre-wrap">
                            {{ $listing->description }}
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('listings.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded">
                            Back to Listings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
