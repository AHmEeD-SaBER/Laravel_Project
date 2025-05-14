<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomUser extends Model
{
    use HasFactory;

    protected $table = 'custom_users';

    protected $fillable = [
        'full_name',
        'user_name',
        'email',
        'phone',
        'whatsapp',
        'address',
        'password',
        'user_image'
    ];

    protected $hidden = [
        'password',
    ];

    // Custom validation rules
    public static function rules()
    {
        return [
            'full_name' => 'required|regex:/^[a-zA-Z\s]{3,50}$/',
            'user_name' => 'required|regex:/^[a-zA-Z0-9_]{4,20}$/|unique:custom_users',
            'email' => 'required|email|unique:custom_users',
            'phone' => 'required|regex:/^[0-9]{11}$/|unique:custom_users',
            'whatsapp' => 'required|regex:/^[0-9]{11}$/',
            'address' => 'required',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/'
            ],
            'user_image' => 'nullable|image|max:5120' // 5MB max
        ];
    }

    // Custom validation messages
    public static function messages()
    {
        return [
            'full_name.required' => 'Full name is required',
            'full_name.regex' => 'Full name must contain only letters and spaces (3-50 characters)',
            'user_name.required' => 'Username is required',
            'user_name.regex' => 'Username must be 4-20 characters of letters, numbers, and underscores',
            'user_name.unique' => 'This username is already taken',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'phone.required' => 'Phone number is required',
            'phone.regex' => 'Phone number must be 11 digits',
            'phone.unique' => 'This phone number is already registered',
            'whatsapp.required' => 'WhatsApp number is required',
            'whatsapp.regex' => 'WhatsApp number must be 11 digits',
            'address.required' => 'Address is required',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.regex' => 'Password must contain at least one number and one special character',
            'user_image.image' => 'The file must be an image',
            'user_image.max' => 'Image size must not exceed 5MB'
        ];
    }

    public static function usernameExists($username)
    {
        return static::where('user_name', $username)->exists();
    }

    public static function emailExists($email)
    {
        return static::where('email', $email)->exists();
    }

    public static function phoneExists($phone)
    {
        return static::where('phone', $phone)->exists();
    }
}
