<?php
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
//Mpesa
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {
    Route::post('mpesa_pay', 'MpesaController@payment_complete')->name('mpesa.pay');

    //Mpesa End

    // RaveController start

    Route::post('/rave_pay', 'FlutterwaveController@initialize')->name('flutterwave.pay');
    Route::get('/rave/callback', 'FlutterwaveController@callback')->name('flutterwave.callback');

    // RaveController end
});
