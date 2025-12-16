<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // Список пользователей
    public function index(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = User::query()->withCount('orders');

        if ($q = $request->query('q')) {
            $query->where(function ($subQuery) use ($q) {
                $subQuery->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    // Просмотр пользователя
    public function show(User $user)
    {
        $user->load('orders');

        return view('admin.users.show', compact('user'));
    }

    // Блокировка/Разблокировка (простой вариант через deleted_at)
    public function toggleBlock(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Нельзя заблокировать себя');
        }

        try {
            if ($user->trashed()) {
                $user->restore();
                $message = 'Пользователь разблокирован';
            } else {
                $user->delete();
                $message = 'Пользователь заблокирован';
            }

            Log::info($message, [
                'admin_id' => auth()->id(),
                'user_id' => $user->id,
            ]);

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка операции');
        }
    }
}
