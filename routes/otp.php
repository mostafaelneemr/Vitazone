<?php

/*
|--------------------------------------------------------------------------
| OTP Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {
    //Verofocation phone
    Route::get('/verification', 'OTPVerificationController@verification')->name('verification');
    Route::post('/verification', 'OTPVerificationController@verify_phone')->name('verification.submit');
    Route::get('/verification/phone/code/resend', 'OTPVerificationController@resend_verificcation_code')->name('verification.phone.resend');

    //Forgot password phone
    Route::get('/password/phone/reset', 'OTPVerificationController@show_reset_password_form')->name('password.phone.form');
    Route::post('/password/reset/submit', 'OTPVerificationController@reset_password_with_code')->name('password.update.phone');
    Route::post('/password/reset/email/submit', 'OTPVerificationController@reset_password_with_code_email')->name('password.update.email');

    //Admin
    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
        Route::get('/otp-configuration', 'OTPController@configure_index')->name('otp.configconfiguration')->middleware(['can:25']);
        Route::get('/otp-credentials-configuration', 'OTPController@credentials_index')->name('otp_credentials.index')->middleware(['can:25']);
        Route::post('/otp-configuration/update/activation', 'OTPController@updateActivationSettings')->name('otp_configurations.update.activation')->middleware(['can:25']);
        Route::post('/otp-credentials-update', 'OTPController@update_credentials')->name('update_credentials')->middleware(['can:25']);

        //Messaging
        Route::get('/sms', 'SmsController@index')->name('sms.index')->middleware(['can:7']);
        Route::post('/sms-send', 'SmsController@send')->name('sms.send')->middleware(['can:7']);
    });
});
