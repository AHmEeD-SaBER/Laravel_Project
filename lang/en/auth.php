<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    'registration' => [
        'title' => 'User Registration',
        'full_name' => 'Full Name',
        'username' => 'Username',
        'email' => 'Email',
        'phone' => 'Phone Number',
        'whatsapp' => 'WhatsApp Number',
        'address' => 'Address',
        'password' => 'Password',
        'confirm_password' => 'Confirm Password',
        'profile_image' => 'Profile Image',
        'register' => 'Register',
    ],
    'validation' => [
        'full_name' => [
            'required' => 'Full name is required',
            'format' => 'Name must contain 3-50 alphabetic characters and spaces only'
        ],
        'username' => [
            'required' => 'Username is required',
            'format' => 'Username must be 4-20 characters of letters, numbers, and underscores',
            'taken' => 'Username is already taken'
        ],
        'email' => [
            'required' => 'Email is required',
            'format' => 'Please enter a valid email address',
            'taken' => 'Email is already registered'
        ],
        'phone' => [
            'required' => 'Phone number is required',
            'format' => 'Phone number must be 11 digits',
            'taken' => 'Phone number is already registered'
        ],
        'whatsapp' => [
            'required' => 'WhatsApp number is required',
            'format' => 'WhatsApp number must be 11 digits',
            'invalid' => 'Invalid WhatsApp number'
        ],
        'address' => [
            'required' => 'Address is required'
        ],
        'password' => [
            'required' => 'Password is required',
            'min' => 'Password must be at least 8 characters',
            'format' => 'Password must contain at least one number and one special character'
        ],
        'confirm_password' => [
            'required' => 'Confirm password is required',
            'match' => 'Passwords do not match'
        ],
        'profile_image' => [
            'format' => 'File must be an image',
            'size' => 'Image size must not exceed 5MB'
        ]
    ],
    'messages' => [
        'success' => 'Registration successful',
        'error' => 'An error occurred. Please try again'
    ]
];
