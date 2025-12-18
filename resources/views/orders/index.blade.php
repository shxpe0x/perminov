<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Мои заказы') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($orders->count() > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                            <!-- Order Header -->
                            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                                <div class="flex flex-wrap items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Заказ №</p>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $order->id }}</p>
                                        </div>
                                        <div class="hidden sm:block w-px h-10 bg-gray-300 dark:bg-gray-600"></div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Дата заказа</p>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ $order->created_at->format('d.m.Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="hidden sm:block w-px h-10 bg-gray-300 dark:bg-gray-600"></div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Статус</p>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                @elseif($order->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @endif">
                                                @switch($order->status)
                                                    @case('pending')
                                                        Ожидает обработки
                                                        @break
                                                    @case('processing')
                                                        В обработке
                                                        @break
                                                    @case('shipped')
                                                        Отправлен
                                                        @break
                                                    @case('delivered')
                                                        Доставлен
                                                        @break
                                                    @case('cancelled')
                                                        Отменён
                                                        @break
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Сумма</p>
                                        <p class="text-xl font-bold text-gray-900 dark:text-white">
                                            {{ number_format($order->total, 0, ',', ' ') }} ₽
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Order Items -->
                            <div class="p-6">
                                <div class="space-y-4">
                                    @foreach($order->items as $item)
                                        <div class="flex gap-4">
                                            <div class="flex-shrink-0 w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                                                @if($item->product->image)
                                                    <img src="{{ $item->product->image_url }}" 
                                                         alt="{{ $item->product->brand }} {{ $item->product->model }}" 
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-1">
                                                <a href="{{ route('catalog.show', $item->product) }}" 
                                                   class="font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $item->product->brand }} {{ $item->product->model }}
                                                </a>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                    Количество: {{ $item->quantity }} шт.
                                                </p>
                                            </div>
                                            
                                            <div class="text-right">
                                                <p class="font-semibold text-gray-900 dark:text-white">
                                                    {{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽
                                                </p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ number_format($item->price, 0, ',', ' ') }} ₽ / шт.
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Delivery Info -->
                                @if($order->delivery_address)
                                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Адрес доставки:</h4>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ $order->delivery_address }}
                                        </p>
                                    </div>
                                @endif
                                
                                @if($order->comment)
                                    <div class="mt-4">
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Комментарий:</h4>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ $order->comment }}
                                        </p>
                                    </div>
                                @endif
                                
                                <!-- Actions -->
                                <div class="mt-6 flex gap-3">
                                    <a href="{{ route('orders.show', $order) }}" 
                                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                                        Подробнее
                                    </a>
                                    
                                    @if(in_array($order->status, ['pending', 'processing']))
                                        <form method="POST" action="{{ route('orders.cancel', $order) }}" 
                                              onsubmit="return confirm('Вы уверены, что хотите отменить заказ?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                                                Отменить заказ
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($order->status === 'delivered')
                                        <a href="{{ route('catalog.index') }}" 
                                           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                                            Повторить заказ
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="mt-8">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-6 text-2xl font-semibold text-gray-900 dark:text-white">
                        У вас пока нет заказов
                    </h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Начните покупки в нашем каталоге
                    </p>
                    <a href="{{ route('catalog.index') }}" 
                       class="mt-6 inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        Перейти в каталог
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>