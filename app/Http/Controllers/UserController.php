<?php

namespace App\Http\Controllers;

use App\Models\CustomUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function show()
    {
        return view('index');
    }
    public function register(Request $request)
    {
        // Server-side validation as backup
        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'regex:/^[a-zA-Z\s]{3,50}$/'],
            'user_name' => ['required', 'regex:/^[a-zA-Z0-9_]{4,20}$/', 'unique:custom_users'],
            'email' => ['required', 'email', 'unique:custom_users'],
            'phone' => ['required', 'regex:/^[0-9]{11}$/', 'unique:custom_users'],
            'whatsapp' => ['required', 'regex:/^[0-9]{11}$/'],
            'password' => ['required', 'min:8', 'regex:/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/'],
            'user_image' => ['nullable', 'image', 'max:5120']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'errors' => $validator->errors()->all()
            ]);
        }

        try {
            $user = new CustomUser();
            $user->full_name = $request->full_name;
            $user->user_name = $request->user_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->whatsapp = $request->whatsapp;
            $user->address = $request->address;
            $user->password = Hash::make($request->password);
            
            if ($request->hasFile('user_image')) {
                $image = $request->file('user_image');
                $imageData = file_get_contents($image->getRealPath());
                $user->user_image = $imageData;
            }
            
            $user->save();

            return response()->json([
                'valid' => true,
                'message' => 'Registration successful'
            ]);
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json([
                'valid' => false,
                'errors' => ['Registration failed. Please try again.']
            ]);
        }
    }
    public function validateField(Request $request)
    {
        $field = $request->field;
        $value = $request->value;
        $type = $request->type;

        // Only handle availability checks here
        switch ($type) {
            case 'username':
                $exists = CustomUser::where('user_name', $value)->exists();
                return response()->json([
                    'valid' => !$exists,
                    'message' => $exists ? 'Username already taken' : 'Username available'
                ]);

            case 'email':
                $exists = CustomUser::where('email', $value)->exists();
                return response()->json([
                    'valid' => !$exists,
                    'message' => $exists ? 'Email already registered' : 'Email available'
                ]);

            case 'phone':
                $exists = CustomUser::where('phone', $value)->exists();
                return response()->json([
                    'valid' => !$exists,
                    'message' => $exists ? 'Phone number already registered' : 'Phone number available'
                ]);

            case 'whatsapp':
                if (!preg_match('/^[0-9]{11}$/', $value)) {
                    return response()->json([
                        'valid' => false,
                        'message' => 'WhatsApp number must be 11 digits'
                    ]);
                }
                $isValidWhatsApp = $this->validateWhatsAppNumber($value);
                return response()->json([
                    'valid' => $isValidWhatsApp,
                    'message' => $isValidWhatsApp ? 'Valid WhatsApp number' : 'Not a valid WhatsApp number'
                ]);

            default:
                return response()->json([
                    'valid' => false,
                    'message' => 'Invalid validation type'
                ]);
        }
    }

    private function validateWhatsAppNumber($phone)
    {
        $phone = preg_replace('/^0/', '', $phone);
        $phone = "20" . $phone;
        $rapidApiKey = 'b419b2bc78msh94c3a81d50ace7ap1181e2jsnf783bcefc7fb';
        $rapidApiHost = 'whatsapp-number-validator3.p.rapidapi.com';
        $endpoint = 'https://whatsapp-number-validator3.p.rapidapi.com/WhatsappNumberHasItWithToken';

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-RapidAPI-Host' => $rapidApiHost,
                'X-RapidAPI-Key' => $rapidApiKey,
            ])->post($endpoint, [
                'phone_number' => $phone
            ]);

            $result = $response->json();
            return isset($result['status']) && $result['status'] === 'valid';
        } catch (\Exception $e) {
            Log::error('WhatsApp validation error: ' . $e->getMessage());
            return true;
        }
    }
}
