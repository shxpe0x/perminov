<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'type' => 'required|in:computer,peripheral',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:9999999',
            'description' => 'nullable|string|max:5000',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:2048',
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
            'type.required' => 'Укажите тип товара',
            'type.in' => 'Тип должен быть: компьютер или периферия',
            'brand.required' => 'Укажите производителя',
            'brand.max' => 'Название производителя слишком длинное',
            'model.required' => 'Укажите модель',
            'model.max' => 'Название модели слишком длинное',
            'price.required' => 'Укажите цену',
            'price.numeric' => 'Цена должна быть числом',
            'price.min' => 'Цена не может быть отрицательной',
            'price.max' => 'Цена слишком высокая',
            'description.max' => 'Описание слишком длинное',
            'category_id.exists' => 'Выбранная категория не существует',
            'image.image' => 'Файл должен быть изображением',
            'image.mimes' => 'Допустимые форматы: JPEG, JPG, PNG, WebP, GIF',
            'image.max' => 'Размер изображения не должен превышать 2 МБ',
        ];
    }
}
