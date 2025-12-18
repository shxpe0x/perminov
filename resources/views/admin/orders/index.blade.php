@extends('layouts.admin')

@section('title', 'Управление заказами')

@section('content')
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Всего заказов: {{ $orders->total() }}</h3>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">№ Заказа</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Клиент</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Дата</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Сумма</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Статус</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Действия</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900 dark:text-white">#{{ $order->id }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $order->user->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->user->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-900 dark:text-white">{{ $order->created_at->format('d.m.Y H:i') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ number_format($order->total, 0, ',', ' ') }} ₽</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    @if($order->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @else bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
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
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="text-blue-600 hover:text-blue-700 font-medium">
                                    Подробнее
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                Заказов пока нет
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endsection