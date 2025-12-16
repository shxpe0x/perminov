<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'delivery_address' => 'required|string|min:10|max:500',
            'phone' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'delivery_address.required' => 'Укажите адрес доставки',
            'delivery_address.min' => 'Адрес слишком короткий (минимум 10 символов)',
            'delivery_address.max' => 'Адрес слишком длинный',
            'phone.required' => 'Укажите телефон',
            'phone.regex' => 'Неверный формат телефона',
            'comment.max' => 'Комментарий слишком длинный',
        ];
    }
}
