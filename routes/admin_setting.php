<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\Settings\ProfileController;


//! This route is for updating the user's profile
Route::controller(ProfileController::class)->group(function () {
    Route::post('/update-profile-picture', 'UpdateProfilePicture')->name('update.profile.picture');
    Route::post('/update-profile-password', 'UpdatePassword')->name('update.Password');

    //! Route for ProfileController
    Route::get('/profile', 'showProfile')->name('profile.setting');
    Route::post('/update-profile', 'UpdateProfile')->name('update.profile');
});

//! Route for System Setting Controller
Route::controller(App\Http\Controllers\Web\Backend\Settings\SystemSettingController::class)->group(function () {
    Route::get('/system-setting', 'index')->name('system.index');
    Route::post('/system-setting/update', 'update')->name('system.update');
});


