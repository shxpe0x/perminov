<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_admin(): void
    {
        $this->get('/admin/products')
            ->assertRedirect('/login');
    }

    public function test_authenticated_non_admin_gets_403_for_admin(): void
    {
        $user = User::query()->create([
            'name' => 'User',
            'email' => 'user@site.ru',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get('/admin/products')
            ->assertForbidden();
    }

    public function test_admin_can_open_admin_products(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@site.ru',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $this->actingAs($admin)
            ->get('/admin/products')
            ->assertOk();
    }
}
