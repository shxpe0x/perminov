@extends('admin.layout')

@section('title', 'Заказ #' . $order->id)

@section('content')
<a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mb-6">
    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
    </svg>
    Назад к заказам
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Items -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Товары в заказе</h3>
            </div>
            <div class="p-6 space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                        @if($item->product->image)
                            <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->brand }} {{ $item->product->model }}" class="w-16 h-16 rounded object-cover">
                        @else
                            <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $item->product->brand }} {{ $item->product->model }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->quantity }} шт. × {{ number_format($item->price, 0) }} ₽</p>
                        </div>
                        <p class="font-medium text-gray-900 dark:text-white">{{ number_format($item->price * $item->quantity, 0) }} ₽</p>
                    </div>
                @endforeach
            </div>
            <div class="p-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-gray-900 dark:text-white">Итого:</span>
                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($order->total, 0) }} ₽</span>
                </div>
            </div>
        </div>

        <!-- Delivery Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Информация о доставке</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-gray-600 dark:text-gray-400">Адрес:</span>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $order->address }}</p>
                </div>
                @if($order->notes)
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Комментарий:</span>
                        <p class="text-gray-900 dark:text-white">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Order Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Информация о заказе</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-gray-600 dark:text-gray-400">Клиент:</span>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $order->user->name }}</p>
                    <p class="text-gray-600 dark:text-gray-400">{{ $order->user->email }}</p>
                </div>
                <div>
                    <span class="text-gray-600 dark:text-gray-400">Дата создания:</span>
                    <p class="text-gray-900 dark:text-white">{{ $order->created_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Status Update -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Изменить статус</h3>
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="status" 
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 mb-4">
                    <option value="pending" @selected($order->status === 'pending')>🕑 Ожидает</option>
                    <option value="processing" @selected($order->status === 'processing')>⚙️ В обработке</option>
                    <option value="shipped" @selected($order->status === 'shipped')>🚚 Отправлен</option>
                    <option value="completed" @selected($order->status === 'completed')>✅ Выполнен</option>
                    <option value="cancelled" @selected($order->status === 'cancelled')>❌ Отменен</option>
                </select>
                <button type="submit" 
                        class="w-full px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg shadow-blue-500/30 transition">
                    Обновить статус
                </button>
            </form>
        </div>
    </div>
</div>
@endsection