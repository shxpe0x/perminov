<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Заказ №{{ $order->id }}
            </h2>
            <a href="{{ route('orders.index') }}" 
               class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                ← К списку заказов
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Информация о заказе
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Дата заказа</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $order->created_at->format('d.m.Y H:i') }}
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Статус</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
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
                            
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Способ оплаты</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $order->payment_method === 'card' ? 'Банковская карта' : 'Наличные' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customer Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Контактные данные
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Имя</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $order->first_name }} {{ $order->last_name }}
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->email }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Телефон</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->phone }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Адрес доставки</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->delivery_address }}</p>
                            </div>
                        </div>
                        
                        @if($order->comment)
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Комментарий</p>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ $order->comment }}</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Order Items -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Товары в заказе
                        </h3>
                        
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex gap-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0 last:pb-0">
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
                                            {{ number_format($item->price, 0, ',', ' ') }} ₽ × {{ $item->quantity }} шт.
                                        </p>
                                    </div>
                                    
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900 dark:text-white">
                                            {{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Итого
                        </h3>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Товары ({{ $order->items->sum('quantity') }} шт.)</span>
                                <span>{{ number_format($order->total, 0, ',', ' ') }} ₽</span>
                            </div>
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Доставка</span>
                                <span class="text-green-600 dark:text-green-400">Бесплатно</span>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex justify-between items-center">
                                <span class="text-xl font-semibold text-gray-900 dark:text-white">Всего:</span>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($order->total, 0, ',', ' ') }} ₽
                                </span>
                            </div>
                        </div>
                        
                        @if(in_array($order->status, ['pending', 'processing']))
                            <form method="POST" action="{{ route('orders.cancel', $order) }}" 
                                  onsubmit="return confirm('Вы уверены, что хотите отменить заказ?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                                    Отменить заказ
                                </button>
                            </form>
                        @endif
                        
                        @if($order->status === 'delivered')
                            <a href="{{ route('catalog.index') }}" 
                               class="block w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-center">
                                Повторить заказ
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>