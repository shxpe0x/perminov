<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'sometimes|required|in:computer,peripheral',
            'brand' => 'sometimes|required|string|max:255',
            'model' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|integer|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'description' => 'nullable|string|max:5000',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,webp,gif',
            'is_featured' => 'nullable|boolean',
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
            'type.required' => 'Тип товара обязателен',
            'type.in' => 'Тип должен быть computer или peripheral',
            'brand.required' => 'Бренд обязателен',
            'model.required' => 'Модель обязательна',
            'price.required' => 'Цена обязательна',
            'price.integer' => 'Цена должна быть числом',
            'price.min' => 'Цена не может быть отрицательной',
            'stock.required' => 'Количество на складе обязательно',
            'stock.integer' => 'Количество должно быть числом',
            'image.image' => 'Файл должен быть изображением',
            'image.max' => 'Размер изображения не должен превышать 2MB',
            'image.mimes' => 'Допустимы только форматы: JPEG, PNG, WebP, GIF',
            'category_id.exists' => 'Выбранная категория не существует',
        ];
    }
}
