@extends('layouts.admin')

@section('title', 'Дашборд')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Всего товаров</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_products'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Всего заказов</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_orders'] }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Выручка</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} ₽</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Пользователей</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_users'] }}</p>
                </div>
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ожидают обработки</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['pending_orders'] }}</p>
                </div>
                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                    <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Товары с низким остатком</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['low_stock_products'] }}</p>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Последние заказы</h3>
            <div class="space-y-3">
                @forelse($recent_orders as $order)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">#{{ $order->id }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->user->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900 dark:text-white">{{ number_format($order->total, 0, ',', ' ') }} ₽</p>
                            <span class="text-xs px-2 py-1 rounded-full 
                                @if($order->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @endif">
                                {{ match($order->status) {
                                    'pending' => 'Ожидает',
                                    'processing' => 'В обработке',
                                    'shipped' => 'Отправлен',
                                    'completed' => 'Завершён',
                                    'cancelled' => 'Отменён',
                                    default => $order->status
                                } }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Заказов пока нет</p>
                @endforelse
            </div>
            <a href="{{ route('admin.orders.index') }}" class="mt-4 block text-center text-blue-600 hover:text-blue-700 font-medium">Все заказы →</a>
        </div>
        
        <!-- Top Products -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Популярные товары</h3>
            <div class="space-y-3">
                @forelse($top_products as $product)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center space-x-3">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->brand }} {{ $product->model }}" class="w-12 h-12 rounded object-cover">
                            @else
                                <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $product->brand }} {{ $product->model }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->orders_count }} заказов</p>
                            </div>
                        </div>
                        <p class="font-medium text-gray-900 dark:text-white">{{ number_format($product->price, 0, ',', ' ') }} ₽</p>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Данных пока нет</p>
                @endforelse
            </div>
            <a href="{{ route('admin.products.index') }}" class="mt-4 block text-center text-blue-600 hover:text-blue-700 font-medium">Все товары →</a>
        </div>
    </div>
@endsection>