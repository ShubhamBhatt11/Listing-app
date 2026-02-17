<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }} - Listings Marketplace</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')
            
            <!-- Page Content -->
            <main>
                <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 sm:p-12 text-center">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">
                        Welcome to Listings
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        A marketplace where providers post listings and admins moderate them.
                    </p>
                    
                    <div class="flex flex-wrap gap-4 justify-center">
                        @auth
                            <!-- Authenticated users go to dashboard -->
                            <a href="{{ route('dashboard') }}" class="bg-green-500 hover:bg-green-600 text-gray px-8 py-3 rounded-lg font-semibold">
                                Go to Dashboard
                            </a>
                        @else
                            <!-- Public users can browse listings -->
                            <a href="{{ route('listings.index') }}" class="bg-blue-500 hover:bg-blue-600 text-black px-8 py-3 rounded-lg font-semibold">
                                Browse Listings
                            </a>
                            <a href="{{ route('login') }}" class="bg-gray-500 hover:bg-gray-600 text-gray px-8 py-3 rounded-lg font-semibold">
                                Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Feature 1: For Everyone -->
                 <!-- /listings -below tag should redirect to this url  -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" onclick="window.location='{{ route('listings.index') }}'" style="cursor: pointer;">
                    <div class="p-6">
                        <div class="text-3xl mb-4">🔍</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Browse Listings</h3>
                        <p class="text-gray-600">
                            Search through approved listings. Filter by city, keyword, or sort by price. No account needed to browse!
                        </p>
                    </div>
                </div>

                <!-- Feature 2: For Providers -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-3xl mb-4">📝</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Create Listings</h3>
                        <p class="text-gray-600">
                            Providers can create and manage their own listings. Manage title, description, price, and location easily.
                        </p>
                    </div>
                </div>

                <!-- Feature 3: For Admins -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-3xl mb-4">✅</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Moderate Content</h3>
                        <p class="text-gray-600">
                            Admins review and moderate listings before they go public. Approve or reject with detailed reasons.
                        </p>
                    </div>
                </div>
            </div>

            <!-- How It Works -->
            <div class="mt-12 bg-gradient-to-r from-blue-50 to-blue-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">How It Works</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600 mb-2">1</div>
                            <h4 class="font-semibold text-gray-900 mb-2">Provider Creates</h4>
                            <p class="text-sm text-gray-600">
                                A provider posts a listing. It stays pending until reviewed.
                            </p>
                        </div>
                        <div class="bg-white p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600 mb-2">2</div>
                            <h4 class="font-semibold text-gray-900 mb-2">Admin Reviews</h4>
                            <p class="text-sm text-gray-600">
                                Admin moderates the listing and approves or rejects it.
                            </p>
                        </div>
                        <div class="bg-white p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600 mb-2">3</div>
                            <h4 class="font-semibold text-gray-900 mb-2">Public Sees It</h4>
                            <p class="text-sm text-gray-600">
                                Approved listings appear publicly for everyone to browse.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ \App\Models\Listing::where('status', 'approved')->count() }}</div>
                    <p class="text-gray-600 mt-2">Approved Listings</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold text-yellow-600">{{ \App\Models\Listing::where('status', 'pending')->count() }}</div>
                    <p class="text-gray-600 mt-2">Pending Review</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold text-gray-600">{{ \App\Models\Listing::count() }}</div>
                    <p class="text-gray-600 mt-2">Total Listings</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold text-green-600">{{ \App\Models\User::count() }}</div>
                    <p class="text-gray-600 mt-2">Users</p>
                </div>
                </div>
            </div>
            </main>
        </div>
    </body>
</html>