<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\CustomUser;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_data()
    {
        $response = $this->postJson('/', [
            'full_name' => 'Ahmed Saber',
            'user_name' => 'ahmadsaberr',
            'email' => 'ahmedd@example.com',
            'phone' => '01234567890',
            'whatsapp' => '01143110070',
            'address' => '123 Main St',
            'password' => 'Password123!',
            'confirm_password' => 'Password123!'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => true,
                'message' => 'Registration successful'
            ]);

        $this->assertDatabaseHas('custom_users', [
            'user_name' => 'ahmadsaberr',
            'email' => 'ahmedd@example.com'
        ]);
    }

    public function test_user_cannot_register_with_invalid_data()
    {
        $response = $this->postJson('/', [
            'full_name' => 'a', // Too short
            'user_name' => 'a', // Too short
            'email' => 'invalid-email',
            'phone' => '123', // Invalid phone format
            'whatsapp' => '123', // Invalid whatsapp format
            'address' => '',
            'password' => 'weak', // Too weak
            'confirm_password' => 'weak'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => false
            ])
            ->assertJsonStructure([
                'valid',
                'errors'
            ]);
    }

    public function test_user_cannot_register_with_duplicate_username()
    {
        // Create a user first
        CustomUser::create([
            'full_name' => 'Existing User',
            'user_name' => 'existinguser',
            'email' => 'existing@example.com',
            'phone' => '01123456789',
            'whatsapp' => '01143110070',
            'address' => '123 Main St',
            'password' => bcrypt('Password123!')
        ]);

        // Try to register with same username
        $response = $this->postJson('/', [
            'full_name' => 'New User',
            'user_name' => 'existinguser', // Duplicate username
            'email' => 'new@example.com',
            'phone' => '01123456788',
            'whatsapp' => '01143110070',
            'address' => '456 Main St',
            'password' => 'Password123!',
            'confirm_password' => 'Password123!'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => false
            ])
            ->assertJsonStructure([
                'valid',
                'errors'
            ]);
    }

    public function test_user_cannot_register_with_duplicate_email()
    {
        // Create a user first
        CustomUser::create([
            'full_name' => 'Existing User',
            'user_name' => 'existinguser',
            'email' => 'existing@example.com',
            'phone' => '01123456789',
            'whatsapp' => '01143110070',
            'address' => '123 Main St',
            'password' => bcrypt('Password123!')
        ]);

        // Try to register with same email
        $response = $this->postJson('/', [
            'full_name' => 'New User',
            'user_name' => 'newuser',
            'email' => 'existing@example.com', // Duplicate email
            'phone' => '01123456788',
            'whatsapp' => '01143110070',
            'address' => '456 Main St',
            'password' => 'Password123!',
            'confirm_password' => 'Password123!'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => false
            ])
            ->assertJsonStructure([
                'valid',
                'errors'
            ]);
    }

    public function test_user_cannot_register_with_duplicate_phone()
    {
        // Create a user first
        CustomUser::create([
            'full_name' => 'Existing User',
            'user_name' => 'existinguser',
            'email' => 'existing@example.com',
            'phone' => '01123456789',
            'whatsapp' => '01143110070',
            'address' => '123 Main St',
            'password' => bcrypt('Password123!')
        ]);

        // Try to register with same phone
        $response = $this->postJson('/', [
            'full_name' => 'New User',
            'user_name' => 'newuser',
            'email' => 'new@example.com',
            'phone' => '01123456789', // Duplicate phone
            'whatsapp' => '01143110070',
            'address' => '456 Main St',
            'password' => 'Password123!',
            'confirm_password' => 'Password123!'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => false
            ])
            ->assertJsonStructure([
                'valid',
                'errors'
            ]);
    }
} 