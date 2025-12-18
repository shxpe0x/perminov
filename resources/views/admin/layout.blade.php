<!DOCTYPE html>
<html lang="ru" class="h-full bg-gray-100 dark:bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Админ-панель') - Perminov Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-gray-900 to-gray-800 border-r border-gray-700">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 border-b border-gray-700">
                <h1 class="text-xl font-bold text-white">👑 Admin Panel</h1>
            </div>

            <!-- Navigation -->
            <nav class="mt-6 px-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition
                          {{ request()->routeIs('admin.dashboard') 
                              ? 'bg-gray-800 text-white shadow-lg' 
                              : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Дашборд
                </a>

                <a href="{{ route('admin.products.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition
                          {{ request()->routeIs('admin.products.*') 
                              ? 'bg-gray-800 text-white shadow-lg' 
                              : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Товары
                </a>

                <a href="{{ route('admin.orders.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition
                          {{ request()->routeIs('admin.orders.*') 
                              ? 'bg-gray-800 text-white shadow-lg' 
                              : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Заказы
                </a>
            </nav>

            <!-- User Section -->
            <div class="absolute bottom-0 w-64 p-4 border-t border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" 
                            class="w-full px-4 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Выйти
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="pl-64">
            <!-- Top Bar -->
            <header class="bg-white dark:bg-gray-800 shadow-sm">
                <div class="px-8 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            @yield('title', 'Дашборд')
                        </h2>
                        <a href="{{ route('catalog.index') }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Перейти на сайт
                        </a>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mx-8 mt-4">
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-8 mt-4">
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main class="p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>