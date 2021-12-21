<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Affiliates\Auth\ForgotPasswordController;
use App\Http\Controllers\Merchants\AffiliateCouponController;
use App\Http\Controllers\Merchants\GroupController;
use App\Http\Controllers\Merchants\SettingController;
use App\Http\Controllers\Merchants\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Merchants\CategoryController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::prefix('admin')->group(function(){
    Route::get('login',[AdminController::class,'login'])->name('admin.login');
    Route::post('login',[AdminController::class,'postLogin'])->name('admin.post_login');
    Route::get('register',[AdminController::class,'register'])->name('admin.register');
    Route::post('register',[AdminController::class,'createAcount'])->name('admin.create_acount');
    Route::get('logout',[AdminController::class,'logout'])->name('admin.logout');
    Route::prefix('dashboard')->middleware('auth:web')->group(function(){
        Route::get('/',[AdminController::class,'dashboard'])->name('admin.dashboard');
        Route::post('/',[AdminController::class, 'postTranslation'])->name('admin.dashboard.post_translation');
        Route::get('/translations-one',[AdminController::class, 'getTranslationsOne'])->name('dashboard.translations_one');
        Route::post('/translations-one',[AdminController::class, 'postTranslationOne']);
        Route::get('/login-as',[AdminController::class,'loginAsMerchant']);
        Route::post('/login-as',[AdminController::class,'postLoginAs']);
        Route::get('/notifications',[AdminController::class,'createNotification']);
        Route::post('/notifications',[AdminController::class,'postNotification']);
    });
});
// Route::get('/', function () {
//     return view('mails.affiliates.denied_referral');
// });
Route::get('/settings/bixgrow-popup-checkout.js',[SettingController::class,'getSettingPopup']);
$domain ='app.'.config('endpoint.main_domain');
Route::domain($domain)->group(function(){
    Route::get('files-download/{id}', [CategoryController::class, 'fileDownLoad']);
});

Route::get('/creatives/{id}/{code}/{groupId}',[CategoryController::class, 'socialCreative']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/email-verify-success', [SettingController::class, 'senderEmailVerifySuccess'])->name('email_verify_success');
Route::get('/email-verify-fail', [SettingController::class, 'senderEmailVerifyFail'])->name('email_verify_fail');
Route::get('shopify-install', [LoginController::class, 'loginFromShopify'])
        // ->middleware(['double-c-shopify-request'])
        ->name('dc.login');
Route::get('/dashboard/upload-file-excel',[DashboardController::class, 'uploadFileExcel']);
Route::post('/dashboard/import-affiliate-translation',[DashboardController::class, 'importAffiliateTranslation']);
Route::get('{any}', function () {
    return view('app');
 })->where('any', '.*');

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::get('/groups', [GroupController::class, 'index'])->name('group.index');
