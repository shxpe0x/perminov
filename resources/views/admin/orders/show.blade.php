@extends('layouts.admin')

@section('title', 'Заказ #' . $order->id)

@section('content')
    <div class="max-w-5xl">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Информация о заказе</h3>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Номер заказа</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">#{{ $order->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Дата создания</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $order->created_at->format('d.m.Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Клиент</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $order->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $order->user->email }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Order Items -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Товары в заказе</h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                @if($item->product->image)
                                    <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->brand }} {{ $item->product->model }}" class="w-20 h-20 rounded object-cover">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $item->product->brand }} {{ $item->product->model }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($item->price, 0, ',', ' ') }} ₽ × {{ $item->quantity }} шт</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($item->subtotal, 0, ',', ' ') }} ₽</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-900 dark:text-white">Итого:</span>
                            <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($order->total, 0, ',', ' ') }} ₽</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Management -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Статус заказа</h3>
                    
                    <div class="mb-4">
                        <span class="px-4 py-2 text-sm font-medium rounded-full inline-block
                            @if($order->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @else bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                            @endif">
                            {{ match($order->status) {
                                'pending' => 'Ожидает обработки',
                                'processing' => 'В обработке',
                                'shipped' => 'Отправлен',
                                'completed' => 'Завершён',
                                'cancelled' => 'Отменён',
                                default => $order->status
                            } }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Изменить статус</label>
                                <select name="status" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Ожидает</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>В обработке</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Отправлен</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Завершён</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Отменён</option>
                                </select>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition">
                                Обновить статус
                            </button>
                        </div>
                    </form>
                </div>

                <a href="{{ route('admin.orders.index') }}" 
                   class="block text-center px-4 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition">
                    ← Назад к списку
                </a>
            </div>
        </div>
    </div>
@endsection>