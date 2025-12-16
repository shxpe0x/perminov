<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="phone" value="Телефон" />
            <x-text-input
                id="phone"
                class="block mt-1 w-full"
                type="text"
                name="phone"
                data-phone-mask
                :value="old('phone')"
                required
                autofocus
                autocomplete="tel"
            />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Пароль" />
            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Запомнить меня</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                Войти
            </x-primary-button>
        </div>

        <div class="mt-4 text-sm text-gray-600">
            Нет аккаунта?
            <a class="underline hover:text-gray-900" href="{{ route('register') }}">Регистрация</a>
        </div>
    </form>
</x-guest-layout>
