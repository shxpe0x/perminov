<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
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
            'rating.required' => 'Поставьте оценку',
            'rating.integer' => 'Оценка должна быть целым числом',
            'rating.min' => 'Минимальная оценка - 1 звезда',
            'rating.max' => 'Максимальная оценка - 5 звёзд',
            'comment.required' => 'Напишите отзыв',
            'comment.min' => 'Отзыв должен содержать минимум 10 символов',
            'comment.max' => 'Отзыв слишком длинный (максимум 1000 символов)',
        ];
    }
}
