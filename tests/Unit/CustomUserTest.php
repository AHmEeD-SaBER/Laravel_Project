<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\CustomUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_username_exists_method()
    {
        // Create a test user
        CustomUser::create([
            'full_name' => 'Test User',
            'user_name' => 'testuser',
            'email' => 'test@example.com',
            'phone' => '01123456789',
            'whatsapp' => '01123456789',
            'address' => '123 Test St',
            'password' => bcrypt('Password123!')
        ]);

        $this->assertTrue(CustomUser::where('user_name', 'testuser')->exists());
        $this->assertFalse(CustomUser::where('user_name', 'nonexistentuser')->exists());
    }

    public function test_email_exists_method()
    {
        // Create a test user
        CustomUser::create([
            'full_name' => 'Test User',
            'user_name' => 'testuser',
            'email' => 'test@example.com',
            'phone' => '01123456789',
            'whatsapp' => '01123456789',
            'address' => '123 Test St',
            'password' => bcrypt('Password123!')
        ]);

        $this->assertTrue(CustomUser::where('email', 'test@example.com')->exists());
        $this->assertFalse(CustomUser::where('email', 'nonexistent@example.com')->exists());
    }

    public function test_phone_exists_method()
    {
        // Create a test user
        CustomUser::create([
            'full_name' => 'Test User',
            'user_name' => 'testuser',
            'email' => 'test@example.com',
            'phone' => '01123456789',
            'whatsapp' => '01123456789',
            'address' => '123 Test St',
            'password' => bcrypt('Password123!')
        ]);

        $this->assertTrue(CustomUser::where('phone', '01123456789')->exists());
        $this->assertFalse(CustomUser::where('phone', '01123456788')->exists());
    }

    public function test_password_is_hidden()
    {
        $user = CustomUser::create([
            'full_name' => 'Test User',
            'user_name' => 'testuser',
            'email' => 'test@example.com',
            'phone' => '01123456789',
            'whatsapp' => '01123456789',
            'address' => '123 Test St',
            'password' => bcrypt('Password123!')
        ]);

        $userArray = $user->toArray();
        $this->assertArrayNotHasKey('password', $userArray);
    }
}