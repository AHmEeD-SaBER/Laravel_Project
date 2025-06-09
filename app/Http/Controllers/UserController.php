<?php

namespace App\Http\Controllers;

use App\Models\CustomUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserRegistered;
use Exception;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Session::has('locale')) {
                App::setLocale(Session::get('locale'));
            } else {
                Session::put('locale', 'en');
                App::setLocale('en');
            }
            return $next($request);
        });
    }

    public function show()
    {
        return view('index');
    }

    public function switchLanguage($locale)
    {
        if (in_array($locale, ['en', 'ar'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);
        }
        return redirect()->back();
    }

    public function register(Request $request)
    {

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

            try {
                Mail::to('ahmadmohmad200020@gmail.com')->send(new NewUserRegistered($user->user_name));

                Log::info('Registration email sent successfully');
            } catch (Exception $e) {
                Log::error('Failed to send registration email. Error details:');
            }

            return response()->json([
                'valid' => true,
                'message' => 'Registration successful'
            ]);
        } catch (Exception $e) {
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