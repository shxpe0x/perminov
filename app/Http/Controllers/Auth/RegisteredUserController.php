<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    private function normalizePhone(string $value): string
    {
        $phone = preg_replace('/\D+/', '', $value);

        if (strlen($phone) === 11 && str_starts_with($phone, '8')) {
            $phone = '7' . substr($phone, 1);
        }

        if (strlen($phone) === 10) {
            $phone = '7' . $phone;
        }

        return $phone;
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Сначала нормализуем, чтобы validate() проверял уже "чистый" формат
        $phone = $this->normalizePhone((string) $request->input('phone'));
        $request->merge(['phone' => $phone]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^7\d{10}$/', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => (string) $request->input('name'),
            'phone' => (string) $request->input('phone'),

            // email не используем (мы убрали reset/verify),
            // но в БД может быть обязательным/unique — поэтому кладём заглушку.
            'email' => $request->input('phone') . '@local.test',

            'password' => Hash::make((string) $request->input('password')),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
