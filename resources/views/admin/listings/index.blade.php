<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Moderate Listings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($listings->count() > 0)
                <div class="space-y-6">
                    @foreach ($listings as $listing)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="mb-4">
                                    <h3 class="text-xl font-semibold text-gray-800">{{ $listing->title }}</h3>
                                    <p class="text-gray-600 text-sm mt-1">
                                        by <strong>{{ $listing->user->name }}</strong> on {{ $listing->created_at->format('M d, Y \a\t H:i') }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                                    <div>
                                        <strong>Location:</strong> {{ $listing->city }}
                                    </div>
                                    <div>
                                        <strong>Price:</strong> ${{ number_format($listing->price_cents / 100, 2) }}
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <strong>Description:</strong>
                                    <p class="text-gray-700 mt-2 whitespace-pre-wrap">{{ $listing->description }}</p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-4 items-start">
                                    <!-- Approve Form -->
                                    <form action="{{ route('admin.listings.approve', $listing) }}" method="POST" class="approve-form">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-gray px-4 py-2 rounded">
                                            Approve
                                        </button>
                                    </form>

                                    <!-- Reject Form -->
                                    <form action="{{ route('admin.listings.reject', $listing) }}" method="POST" class="reject-form" style="flex: 1;">
                                        @csrf
                                        <div class="flex gap-2 items-end">
                                            <div class="flex-1">
                                                <input type="text" name="rejection_reason" placeholder="Rejection reason (required)" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 text-sm
                                                    @error('rejection_reason') border-red-500 @enderror"
                                                    maxlength="255"
                                                    value="{{ old('rejection_reason') }}">
                                                @error('rejection_reason')
                                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-gray px-4 py-2 rounded whitespace-nowrap">
                                                Reject
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $listings->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-600">
                        <p>{{ __('No pending listings to moderate.') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
