<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
        @csrf
        @method('delete')

        <div>
            <x-input-label for="password" value="{{ __('Password') }}" />

            <x-text-input
                id="password"
                name="password"
                type="password"
                class="mt-1 block w-full sm:w-3/4"
                placeholder="{{ __('Password') }}"
            />

            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
        </div>

        <x-danger-button>{{ __('Delete Account') }}</x-danger-button>
    </form>
</section>
