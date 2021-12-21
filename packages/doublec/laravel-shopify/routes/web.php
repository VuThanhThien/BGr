<?php

use DoubleC\LaravelShopify\Http\Controllers\Admin\AdminController;
use DoubleC\LaravelShopify\Http\Controllers\AppController;
use DoubleC\LaravelShopify\Http\Controllers\BillingController;
use DoubleC\LaravelShopify\Http\Controllers\Controller;
use DoubleC\LaravelShopify\Http\Controllers\ErrorController;
use DoubleC\LaravelShopify\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::domain('app.'.config('endpoint.main_domain'))->group(function () {
    Route::middleware(['web'])->namespace(Controller::class)->group(function () {
        /**
         * --------------------------
         * Base routes [Installation]
         * --------------------------
         */
        # Install application
        Route::get("bixgrow-install", [AppController::class, 'install'])
            ->name('dc.install');
        Route::post("install-app", [AppController::class, 'postInstall'])
            ->name('dc.install-app');
        # Login from shopify | Authorize app
        Route::get('shopify-install', [AppController::class, 'loginShopify'])
            ->middleware(['double-c-shopify-request'])
            ->name('dc.login');
        Route::get('authorize', [AppController::class, 'authorizeApp'])
            ->middleware(['double-c-shopify-request'])
            ->name('dc.authorize');
        # Errors
        // Route::get('display-message', [ErrorController::class, 'displayErrorMessage'])
        //     ->name('dc.display-message');
        # Charge | Billing
        // Route::get('active_charge', [AppController::class, "activeCharge"]);
        // Route::get('plans', [BillingController::class, 'plans'])
        //     ->middleware(['double-c-shop-auth'])
        //     ->name('dc.plans');
        Route::post('subscribe-plan', [BillingController::class, 'subscribePlan'])
            ->middleware(['double-c-shop-auth'])
            ->name('dc.subscribe-plan');
        /**
         * --------------------------
         * Admin Controller [SUDO]
         * --------------------------
         */
        // Route::prefix('admin')->middleware(['double-c-shop-admin', 'double-c-shop-auth'])->group(function () {
            // Route::view('sudo', 'laravel-shopify::pages.admin.sudo-login');
            // Route::post('sudo', [AdminController::class, 'sudoLogin'])->name('dc.admin.sudo');
        // });
    });
    
});

Route::domain('api.'.config('endpoint.main_domain'))->group(function () {
    Route::namespace(Controller::class)->group(function () {
        /**
         * --------------------------
         * Base routes [Webhook listener]
         * --------------------------
         */
        Route::post('uninstalled', [WebhookController::class, 'appUninstalledWebhook'])
            ->middleware(['double-c-valid-webhook'])
            ->name('dc.app-uninstalled-webhook');
    });
});


