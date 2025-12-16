<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Нормализуем телефон к формату "только цифры".
     * Хотим хранить/искать строго: 79991234567 (11 цифр, начинается с 7).
     */
    private function normalizePhone(string $value): string
    {
        $phone = preg_replace('/\D+/', '', $value); // оставить только цифры

        // Если кто-то ввёл 8XXXXXXXXXX, приводим к 7XXXXXXXXXX
        if (strlen($phone) === 11 && str_starts_with($phone, '8')) {
            $phone = '7' . substr($phone, 1);
        }

        // Если пришло 10 цифр (без "7"), можно автоматически добавить 7
        // (маска обычно даёт 10 цифр без префикса, если брать unmaskedValue)
        if (strlen($phone) === 10) {
            $phone = '7' . $phone;
        }

        return $phone;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // форму валидируем мягко (просто строка),
            // а строгую проверку делаем после нормализации (см. authenticate()).
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $phone = $this->normalizePhone((string) $this->input('phone'));

        // Строгая проверка уже нормализованного значения (11 цифр, РФ)
        if (!preg_match('/^7\d{10}$/', $phone)) {
            throw ValidationException::withMessages([
                'phone' => 'Введите телефон в формате +7 (999) 999 99 99',
            ]);
        }

        if (! Auth::attempt(['phone' => $phone, 'password' => (string) $this->input('password')], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'phone' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'phone' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        $phone = $this->normalizePhone((string) $this->input('phone'));

        // Можно без Str::lower, т.к. цифры, но оставим стиль Breeze (transliterate + ip)
        return Str::transliterate($phone . '|' . $this->ip());
    }
}
