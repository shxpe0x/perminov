<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Проверка, является ли пользователь администратором
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Форматированный номер телефона
     */
    public function getFormattedPhoneAttribute(): string
    {
        if (empty($this->phone)) {
            return '';
        }

        // Предполагаем формат +7XXXXXXXXXX
        if (preg_match('/^(\+?7)(\d{3})(\d{3})(\d{2})(\d{2})$/', $this->phone, $matches)) {
            return "{$matches[1]} ({$matches[2]}) {$matches[3]}-{$matches[4]}-{$matches[5]}";
        }

        return $this->phone;
    }
}
