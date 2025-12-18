<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'delivery_address' => 'required|string|max:500',
            'delivery_method' => 'required|in:courier,pickup,post',
            'payment_method' => 'required|in:card,cash,online',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'delivery_address.required' => 'Адрес доставки обязателен',
            'delivery_method.required' => 'Выберите способ доставки',
            'delivery_method.in' => 'Неверный способ доставки',
            'payment_method.required' => 'Выберите способ оплаты',
            'payment_method.in' => 'Неверный способ оплаты',
        ];
    }
}
