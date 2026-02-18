<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <a href="{{ route('listings.create') }}" class="bg-blue-500 hover:bg-blue-600 text-gray px-4 py-2 rounded">
                Create Listing
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php($providerListings = auth()->user()->listings()->latest()->get())

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($providerListings->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        {{ __("You haven't created any listings yet. ") }}
                        <a href="{{ route('listings.create') }}" class="text-blue-500 hover:underline">Create one</a>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2 px-4">Title</th>
                                    <th class="text-left py-2 px-4">City</th>
                                    <th class="text-left py-2 px-4">Price</th>
                                    <th class="text-left py-2 px-4">Status</th>
                                    <th class="text-left py-2 px-4">Created</th>
                                    <th class="text-left py-2 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($providerListings as $listing)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 px-4">{{ $listing->title }}</td>
                                        <td class="py-2 px-4">{{ $listing->city }}</td>
                                        <td class="py-2 px-4">${{ number_format($listing->price_cents / 100, 2) }}</td>
                                        <td class="py-2 px-4">
                                            <span class="px-2 py-1 rounded text-sm
                                                @if ($listing->status === 'approved')
                                                    bg-green-100 text-green-800
                                                @elseif ($listing->status === 'pending')
                                                    bg-yellow-100 text-yellow-800
                                                @else
                                                    bg-red-100 text-red-800
                                                @endif
                                            ">
                                                {{ ucfirst($listing->status) }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4">{{ $listing->created_at->format('M d, Y') }}</td>
                                        <td class="py-2 px-4">
                                            <a href="{{ route('listings.edit', $listing) }}" class="text-blue-500 hover:underline">Edit</a>
                                        </td>
                                    </tr>
                                    @if ($listing->status === 'rejected' && $listing->rejection_reason)
                                        <tr class="border-b bg-red-50">
                                            <td colspan="6" class="py-2 px-4 text-sm text-red-700">
                                                <strong>Rejection reason:</strong> {{ $listing->rejection_reason }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
