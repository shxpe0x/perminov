<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'type' => is_string($this->type) ? trim($this->type) : $this->type,
            'brand' => is_string($this->brand) ? trim($this->brand) : $this->brand,
            'model' => is_string($this->model) ? trim($this->model) : $this->model,
            'description' => is_string($this->description) ? trim($this->description) : $this->description,
            'price' => is_numeric($this->price) ? (int) $this->price : $this->price,
        ]);
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:computer,peripheral'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Укажи тип товара.',
            'type.in' => 'Тип должен быть computer или peripheral.',
            'brand.required' => 'Укажи бренд.',
            'model.required' => 'Укажи модель.',
            'price.required' => 'Укажи цену.',
            'price.integer' => 'Цена должна быть числом.',
            'price.min' => 'Цена не может быть отрицательной.',
        ];
    }
}
