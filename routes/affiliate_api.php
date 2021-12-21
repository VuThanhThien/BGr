<?php


use App\Http\Controllers\Affiliates\Auth\RegisterController;
use App\Http\Controllers\Affiliates\Auth\LoginController;
use App\Http\Controllers\Affiliates\DashboardController;
use App\Http\Controllers\Affiliates\SettingController;
use App\Http\Controllers\Affiliates\LayoutController;
use App\Http\Controllers\Affiliates\ConversionController;
use App\Http\Controllers\Affiliates\PayoutController;
use App\Http\Controllers\Affiliates\AffiliateController;
use App\Http\Controllers\Affiliates\AffiliateCouponController;
use App\Http\Controllers\Affiliates\Auth\ForgotPasswordController;
use App\Http\Controllers\Affiliates\CategoryController;

Route::middleware(['auth:affiliate-api', 'cors'])->prefix('partner')->group(function () {

    Route::get('/verify',[LoginController::class, 'verify']);
    Route::get('/layout-data', [LayoutController::class, 'getData']);
    Route::post('/settings/update-payment-method', [SettingController::class, 'updatePaymentMethod']);
    Route::post('/settings/update-password', [SettingController::class, 'updatePassword']);
    Route::post('/settings/update-profile', [SettingController::class, 'updateProfile']);
    Route::get('/settings', [SettingController::class, 'index']);
    Route::get('/dashboard/performance', [DashboardController::class, 'getPerformance']);
    Route::get('/translations/{locale?}',[LayoutController::class, 'getTranslations']);
    Route::get('/settings/affiliate-account-language',[LayoutController::class, 'getAffiliateAcountLanguage']);

    //Conversion
    Route::get('/conversions', [ConversionController::class, 'index'] );
    Route::get('conversions/get-statistics',[ConversionController::class, 'getStatistics']);
    Route::get('conversions/commission-statistics',[ConversionController::class,'getCommissionStatistics']);
    Route::get('conversions/{id}/network-commission',[ConversionController::class, 'getNetworkCommission']);

    //Payouts
    Route::get('/payouts',[PayoutController::class,'getPaidPayment'] );
    Route::get('/payouts/{id}/orders',[PayoutController::class,'getPaymentOrder']);
    Route::get('/payouts/store-credit-coupons',[PayoutController::class,'getStoreCreditCoupon']);
    Route::get('payouts/store-credit-walet',[PayoutController::class, 'getStoreCreditWalet']);
    Route::post('payouts/autopay/discount-code',[PayoutController::class, 'storeDiscountCode']);
    
    //Affiliate
    Route::get('affiliates/{id}',[AffiliateController::class,'getAffiliate']);
    Route::get('affiliates/commission-statistics',[AffiliateController::class,'getCommissionStatistics']);
    Route::post('/affiliates/network-statistics', [AffiliateController::class,'getNetworkStatistics']);
    Route::post('affiliates/set-custom-aff-link', [AffiliateController::class, 'setCustomAffLink']);
    Route::put('affiliates/update-custom-aff-link', [AffiliateController::class, 'updateCustomAffLink']);


    //Coupon
    Route::get('/coupons',[AffiliateCouponController::class,'getAffiliateCoupon']);

    //Category
    Route::get('/categorys/get-file-category/{id}', [CategoryController::class, 'getFileCategory']);
});


Route::prefix('partner')->group(function () {
        Route::get('/register', [RegisterController::class, 'getRegister'] );
        Route::post('/register', [RegisterController::class, 'register'] );
        Route::get('/login', [LoginController::class, 'getLogin'] );
        Route::post('/login', [LoginController::class, 'login'] );
});
Route::post('/partner/password/reset',[ForgotPasswordController::class,'forgot']);
Route::post('/partner/password/reset_password',[ForgotPasswordController::class,'reset']);
Route::get('/partner/login/logo-shop', [DashboardController::class, 'getLogoShop']);
Route::post('/partner/checkout_popup_register',[RegisterController::class,'checkoutPopupRegister']);
Route::post('/partner/is_show_checkout_popup_register',[RegisterController::class,'isShowPopupRegister']);
