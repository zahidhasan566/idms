<?php

use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'jwt:api'], function () {
    Route::post('sap-product-add', [\App\Http\Controllers\Sap\CommonSapController::class, 'storeSapProduct']);
    Route::post('sap-customer-add', [\App\Http\Controllers\Sap\CommonSapController::class, 'storeSapCustomer']);
    Route::post('prebooking-add', [\App\Http\Controllers\Sap\PrebookingController::class, 'storePreBookingCustomer']);
    Route::post('sap-invoice-add', [\App\Http\Controllers\Sap\SapInvoiceController::class, 'storeSapInvoice']);

});



//Route::group(['middleware' => ['jwt:api']], function () {
//
//});
