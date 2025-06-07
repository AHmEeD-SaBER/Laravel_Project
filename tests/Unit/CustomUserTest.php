<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\CustomUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_rules_are_correct()
    {
        $rules = CustomUser::rules();
        
        $this->assertArrayHasKey('full_name', $rules);
        $this->assertArrayHasKey('user_name', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('phone', $rules);
        $this->assertArrayHasKey('whatsapp', $rules);
        $this->assertArrayHasKey('password', $rules);
        $this->assertArrayHasKey('user_image', $rules);
    }

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

        $this->assertTrue(CustomUser::usernameExists('testuser'));
        $this->assertFalse(CustomUser::usernameExists('nonexistentuser'));
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

        $this->assertTrue(CustomUser::emailExists('test@example.com'));
        $this->assertFalse(CustomUser::emailExists('nonexistent@example.com'));
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

        $this->assertTrue(CustomUser::phoneExists('01123456789'));
        $this->assertFalse(CustomUser::phoneExists('01123456788'));
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