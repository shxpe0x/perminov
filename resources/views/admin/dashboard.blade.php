@extends('admin.layout')

@section('title', 'Дашборд')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Total Products -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Всего товаров</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_products'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
        </div>
        @if($stats['low_stock_products'] > 0)
            <p class="text-sm text-orange-600 dark:text-orange-400 mt-2">
                ⚠️ {{ $stats['low_stock_products'] }} товаров заканчивается
            </p>
        @endif
    </div>

    <!-- Total Orders -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Всего заказов</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </div>
        @if($stats['pending_orders'] > 0)
            <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-2">
                🕑 {{ $stats['pending_orders'] }} ожидают обработки
            </p>
        @endif
    </div>

    <!-- Total Revenue -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Выручка</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} ₽</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Последние заказы</h3>
        </div>
        <div class="p-6">
            @forelse($recent_orders as $order)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">#{{ $order->id }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->user->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900 dark:text-white">{{ number_format($order->total, 0) }} ₽</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ match($order->status) {
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                            } }}">
                            {{ match($order->status) {
                                'pending' => 'Ожидает',
                                'processing' => 'В обработке',
                                'shipped' => 'Отправлен',
                                'completed' => 'Выполнен',
                                'cancelled' => 'Отменен',
                                default => $order->status
                            } }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 dark:text-gray-400 text-center py-8">Заказов пока нет</p>
            @endforelse
        </div>
    </div>

    <!-- Top Products -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Популярные товары</h3>
        </div>
        <div class="p-6">
            @forelse($top_products as $product)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                    <div class="flex items-center">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->brand }} {{ $product->model }}" class="w-10 h-10 rounded object-cover">
                        @else
                            <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="ml-3">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $product->brand }} {{ $product->model }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $product->orders_count }} продаж</p>
                        </div>
                    </div>
                    <p class="font-medium text-gray-900 dark:text-white">{{ number_format($product->price, 0) }} ₽</p>
                </div>
            @empty
                <p class="text-gray-600 dark:text-gray-400 text-center py-8">Данных пока нет</p>
            @endforelse
        </div>
    </div>
</div>
@endsection