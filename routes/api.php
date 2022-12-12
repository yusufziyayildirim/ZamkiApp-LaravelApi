<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/send-reset-password-email', [PasswordResetController::class, 'send_reset_password_email']);
Route::post('/email/verification-notification', [VerifyEmailController::class, 'resendNotification'])->name('verification.send');

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/loggeduser', [AuthController::class, 'logged_user']);

    Route::post('/changepassword', [ChangePasswordController::class, 'change_password']);

    Route::get('/getalluser', [UserController::class, 'getAllUser']);
    Route::get('/getuser', [UserController::class, 'getUser']);
    Route::post('/setuserlanguage', [UserController::class, 'setUserLanguage']);
    Route::post('/update/nativeinlanguage', [UserController::class, 'updateNativeInLanguage']);
    Route::post('/update/alsospeakinglanguage', [UserController::class, 'updateAlsoSpeakingLanguage']);
    Route::post('/update/learninglanguage', [UserController::class, 'updateLearningLanguage']);
    Route::post('/update/profile', [UserController::class, 'updateProfile']);

    Route::get('/getreferences', [UserController::class, 'getUserReference']);
});
