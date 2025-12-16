<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $u = User::query()->firstOrCreate(
            ['email' => 'admin@admin.ru'],
            ['name' => 'Admin', 'password' => Hash::make('admin12345')]
        );

        $u->is_admin = true;
        $u->save();
    }
}
