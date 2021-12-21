<?php

use App\Http\Controllers\Affiliates\DashboardController;
use App\Http\Controllers\Affiliates\AppController;
use Illuminate\Support\Facades\Route;

$domain = config('endpoint.main_domain');

Route::domain('{subdomain}.'.$domain)->group(function () {
    Route::get('login-as', [AppController::class, 'loginAs']);
    Route::get('{any}', function () {
        return view('affiliate');
     })->where('any', '.*')->name('affiliate_dasboard');
    // Route::get('/', [DashboardController::class, 'index']);
    // Route::get('affiliate', [DashboardController::class, 'index']);
});
