<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Оформление заказа') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('orders.store') }}">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Order Form -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Contact Information -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Контактная информация
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Имя *
                                    </label>
                                    <input type="text" 
                                           name="first_name" 
                                           value="{{ old('first_name', auth()->user()->name) }}" 
                                           required
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                    @error('first_name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Фамилия *
                                    </label>
                                    <input type="text" 
                                           name="last_name" 
                                           value="{{ old('last_name') }}" 
                                           required
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email *
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           value="{{ old('email', auth()->user()->email) }}" 
                                           required
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Телефон *
                                    </label>
                                    <input type="tel" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           data-phone-mask
                                           required
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Delivery Address -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Адрес доставки
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Город *
                                    </label>
                                    <input type="text" 
                                           name="city" 
                                           value="{{ old('city') }}" 
                                           required
                                           placeholder="Например: Москва"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Улица *
                                        </label>
                                        <input type="text" 
                                               name="street" 
                                               value="{{ old('street') }}" 
                                               required
                                               placeholder="Например: ул. Ленина"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                        @error('street')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Дом *
                                        </label>
                                        <input type="text" 
                                               name="house" 
                                               value="{{ old('house') }}" 
                                               required
                                               placeholder="12"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                        @error('house')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Квартира
                                        </label>
                                        <input type="text" 
                                               name="apartment" 
                                               value="{{ old('apartment') }}" 
                                               placeholder="45"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                        @error('apartment')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Почтовый индекс
                                        </label>
                                        <input type="text" 
                                               name="postal_code" 
                                               value="{{ old('postal_code') }}" 
                                               placeholder="123456"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                        @error('postal_code')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Method -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Способ оплаты
                            </h3>
                            
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                    <input type="radio" 
                                           name="payment_method" 
                                           value="card" 
                                           checked
                                           class="text-blue-600 focus:ring-blue-500">
                                    <div class="ml-3">
                                        <div class="font-medium text-gray-900 dark:text-white">Банковской картой онлайн</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Visa, MasterCard, Мир</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                    <input type="radio" 
                                           name="payment_method" 
                                           value="cash"
                                           class="text-blue-600 focus:ring-blue-500">
                                    <div class="ml-3">
                                        <div class="font-medium text-gray-900 dark:text-white">Наличными при получении</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Оплата курьеру</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Comments -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Комментарий к заказу
                            </h3>
                            
                            <textarea name="comment" 
                                      rows="3" 
                                      placeholder="Дополнительные пожелания по доставке..."
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">{{ old('comment') }}</textarea>
                        </div>
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Ваш заказ
                            </h3>
                            
                            <div class="space-y-3 mb-6 max-h-64 overflow-y-auto">
                                @foreach($cart->items as $item)
                                    <div class="flex gap-3 text-sm">
                                        <div class="flex-shrink-0 w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded">
                                            @if($item->product->image)
                                                <img src="{{ $item->product->image_url }}" 
                                                     alt="{{ $item->product->brand }}" 
                                                     class="w-full h-full object-cover rounded">
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-gray-900 dark:text-white font-medium truncate">
                                                {{ $item->product->brand }} {{ $item->product->model }}
                                            </p>
                                            <p class="text-gray-500 dark:text-gray-400">
                                                {{ $item->quantity }} шт. × {{ number_format($item->product->price, 0, ',', ' ') }} ₽
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                {{ number_format($item->product->price * $item->quantity, 0, ',', ' ') }} ₽
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2 mb-6">
                                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                    <span>Товары</span>
                                    <span>{{ number_format($cart->total, 0, ',', ' ') }} ₽</span>
                                </div>
                                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                    <span>Доставка</span>
                                    <span class="text-green-600 dark:text-green-400">Бесплатно</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Итого:</span>
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ number_format($cart->total, 0, ',', ' ') }} ₽
                                    </span>
                                </div>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                Подтвердить заказ
                            </button>
                            
                            <p class="mt-4 text-xs text-gray-500 dark:text-gray-400 text-center">
                                Нажимая кнопку, вы соглашаетесь с условиями оферты
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>