<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ServiceRequestController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;





//Register API
Route::controller(RegisterController::class)->prefix('users/register')->group(function () {
    // User Register
    Route::post('/', 'userRegister');

    // Verify OTP
    Route::post('/otp-verify', 'otpVerify');

    // Resend OTP
    Route::post('/otp-resend', 'otpResend');
});

//Login API
Route::controller(LoginController::class)->prefix('users/login')->group(function () {

    // User Login
    Route::post('/', 'userLogin');
   
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/data', 'userData');
        Route::post('/logout', 'logoutUser');
    });

    //Customer Service Routes
    Route::group(['middleware' => ['customer']], function () {
        Route::controller(CustomerController::class)->group(function () {
           Route::get('/services', 'getAllServices');
        });

        //Booking route
        Route::controller(BookingController::class)->group(function () {
            Route::post('/create/booking/{id}', 'createBooking');
            Route::get('/my-booking', 'myBooking');
        });
    });

    // Admin Service Routes
    Route::group(['middleware' => ['admin']], function () {
       Route::controller(ServiceController::class)->group(function () {
            Route::post('/create/service', 'createService');
            Route::post('/update/service/{id}', 'updateService');
            Route::delete('/delete/service/{id}', 'deleteService');
        });

        Route::controller(ServiceRequestController::class)->group(function () {
            Route::get('/service-requests', 'getAllServiceRequests');
        });
    });
});
