<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', [UserController::class, 'show'])->name('register');
Route::post('/', [UserController::class, 'register'])->name('register.submit');
Route::post('/validate-field', [UserController::class, 'validateField'])->name('validate.field');
Route::get('language/{locale}', [UserController::class, 'switchLanguage'])->name('language.switch');

Route::get('/test-mail', function () {
    try {
        Mail::raw('Test email from Laravel', function($message) {
            $message->to('ahmadmohmad200020@gmail.com')
                    ->subject('Test Email');
        });
        return 'Test email sent successfully!';
    } catch (Exception $e) {
        return 'Error sending email: ' . $e->getMessage();
    }
});
