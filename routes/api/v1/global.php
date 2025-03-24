<?php

use App\Http\Controllers\Api\V1\GlobalController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\Settings\BasicSettingsController;
use Illuminate\Support\Facades\Route;

    Route::controller(BasicSettingsController::class)->group(function(){
        Route::get('basic-settings','basicSettings');
        Route::get("languages","getLanguages")->withoutMiddleware(['system.maintenance.api']);
        Route::get('splash-screen','splashScreen');
        Route::get('onboard-screen','onboardScreen');
    });

//    Route::controller(GlobalController::class)->group(function(){
//        Route::get('car/area','carArea');
//        Route::get('car/type','carType');
//        Route::get('cars','viewCar');
//        Route::post('car/booking','store');
//        Route::post('area/types','getAreaTypes');
//        Route::post('search/car','searchCar');
//        Route::get('confirm','confirm');
//        Route::post('booking/verify','mailVerify');
//        Route::get('mail/resend', 'mailResendToken');
//    });


Route::controller(ServiceController::class)->group(function(){
    Route::get('service/area','serviceArea');
    Route::get('service/type','serviceType');
    Route::get('services','viewService');
    Route::post('service/booking','store');
    Route::post('area/types','getAreaTypes');
    Route::post('search/service','searchService');
    Route::get('confirm','confirm');
    Route::post('booking/verify','mailVerify');
    Route::get('mail/resend', 'mailResendToken');
});
?>
