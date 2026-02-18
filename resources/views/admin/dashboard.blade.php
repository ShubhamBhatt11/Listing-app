<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <a href="{{ route('admin.listings.index') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                Moderate Listings
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $approvedCount }}</div>
                    <p class="text-gray-600 mt-2">Approved Listings</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold text-yellow-600">{{ $pendingCount }}</div>
                    <p class="text-gray-600 mt-2">Pending Review</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold text-gray-600">{{ $usersCount }}</div>
                    <p class="text-gray-600 mt-2">Total Users</p>
                </div>
            </div>

            <div class="mt-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Pending Listings</h3>
                    @if($pendingListings->count())
                        <ul class="space-y-4">
                            @foreach($pendingListings as $listing)
                                <li class="flex justify-between items-center">
                                    <div>
                                        <div class="font-semibold">{{ $listing->title }}</div>
                                        <div class="text-sm text-gray-600">by {{ $listing->user->name }} — {{ $listing->city }}</div>
                                    </div>
                                    <a href="{{ route('admin.listings.index') }}#listing-{{ $listing->id }}" class="text-sm text-red-500 hover:underline">Moderate</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-600">No pending listings.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
