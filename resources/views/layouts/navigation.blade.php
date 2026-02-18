<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-6">
                <a href="{{ route('listings.index') }}" class="shrink-0 flex items-center">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>

                <a href="{{ route('listings.index') }}" class="text-sm text-gray-700 hover:text-gray-900">
                    Listings
                </a>

                @auth
                    @if (auth()->user()->isProvider())
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-gray-900">
                            Dashboard
                        </a>
                    @endif

                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-700 hover:text-gray-900">
                            Admin Dashboard
                        </a>

                        <a href="{{ route('admin.listings.index') }}" class="text-sm text-gray-700 hover:text-gray-900">
                            Moderate Listings
                        </a>
                    @endif
                @endauth
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-700">Log Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Login</a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-gray-900">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
