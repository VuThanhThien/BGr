<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Merchants\Auth\LoginController;
use App\Http\Controllers\Merchants\GroupController;
use App\Http\Controllers\Merchants\ConversionController;
use App\Http\Controllers\Merchants\AffiliateController;
use App\Http\Controllers\Merchants\ProductCommissionController;
use App\Http\Controllers\Merchants\DashboardController;
use App\Http\Controllers\Merchants\SettingController;
use App\Http\Controllers\Merchants\LayoutController;
use App\Http\Controllers\Merchants\PayoutController;
use App\Http\Controllers\Merchants\AffiliateCouponController;
use App\Http\Controllers\Merchants\AffiliateTierController;
use App\Http\Controllers\Merchants\EmailTemplateController;
use App\Http\Controllers\Merchants\ShopSettingController;
use App\Http\Controllers\Merchants\CategoryController;
use App\Http\Controllers\Merchants\QuickStartController;
use App\Http\Controllers\Merchants\Auth\RegisterController;
use App\Http\Controllers\Merchants\FeedBackController;
use App\Http\Controllers\Merchants\IntegrationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::get('/verify',[LoginController::class, 'verify']);
        Route::get('/init-data', [DashboardController::class, 'getInitData']);
        Route::get('/layout-data', [LayoutController::class, 'getData']);
        Route::post('/register', [RegisterController::class, 'register'] );
        Route::post('affiliates/import',[RegisterController::class,'importAffiliate']);
        //QuickStart
        Route::get('/groups/default-group',[QuickStartController::class, 'defaultGroup']);
        Route::post('/logos/upload-logo', [QuickStartController::class, 'uploadLogo']);
        Route::put('/shopsettings/upload-logo-shop',[QuickStartController::class,'uploadLogoShop']);
     //Dashoboard
        Route::get('/dashboard/growth-statistic',[DashboardController::class, 'getGrowthStatistic']);
        Route::get('/dashboard/performance',[DashboardController::class,'getPerformance']);
        Route::get('/dashboard/top-affiliate',[DashboardController::class,'getTopAffiliate']);
        Route::get('/dashboard/recent-sale',[DashboardController::class,'getRecentSale']);
        //Group
        Route::get('/groups',[GroupController::class, 'index']);
        Route::post('/groups',[GroupController::class, 'store']);
        Route::post('/groups/duplicate',[GroupController::class, 'duplicate']);
        Route::get('/groups/{id}',[GroupController::class, 'show']);
        Route::put('/groups/{id}',[GroupController::class, 'update']);
        Route::put('/groups/{id}/change-active',[GroupController::class, 'changeActive']);
        Route::put('/groups/{id}/set-default',[GroupController::class, 'setDefault']);
        Route::get('/groups/{id}',[GroupController::class, 'show']);
        Route::put('/groups/{id}/mlm',[GroupController::class, 'updateMLM']);
        Route::put('/groups/{id}/registration_form',[GroupController::class, 'updateRegistrationForm']);
        Route::delete('/groups/{id}',[GroupController::class,'destroy']);
        Route::get('/groups/{id}/checkAffilate',[GroupController::class,'checkAffilate']);
        Route::post('/groups/uploadFile',[GroupController::class, 'uploadAsideImage']);

        //Conversion
        Route::get('/conversions', [ConversionController::class, 'index'] );
        Route::put('conversions/{id}/approve', [ConversionController::class, 'approve']);
        Route::put('conversions/{id}/reject', [ConversionController::class,'reject']);
        Route::put('conversions/{id}/undo', [ConversionController::class,'undo']);
        Route::get('conversions/get-statistics',[ConversionController::class,'getStatistics']);
        Route::get('conversions/commission-statistics',[ConversionController::class,'getCommissionStatistics']);
        Route::put('conversions/approve-converstions', [ConversionController::class, 'approveConversions']);
        Route::put('conversions/deny-converstions', [ConversionController::class, 'denyConversions']);
        Route::get('conversions/{id}/network-explanation', [ConversionController::class, 'networkExplanation']);
        Route::post('/conversions', [ConversionController::class, 'store']);
        Route::post('/conversions/{id}/comment', [ConversionController::class, 'saveComment']);
        Route::put('conversions/bulk_approve', [ConversionController::class,'bulkApprove']);
        Route::put('conversions/bulk_deny', [ConversionController::class,'bulkDeny']);

        //Affiliate
        Route::get('affiliates/export',[AffiliateController::class,'exportAffiliate']);
        Route::post('affiliates/upload-file-excel',[AffiliateController::class,'uploadFileExcel']);
        Route::get('/affiliates/search', [AffiliateController::class, 'search']);
        Route::get('/affiliates', [AffiliateController::class, 'index']);
        Route::post('/affiliates/change-status', [AffiliateController::class, 'changeStatus']);
        Route::get('/affiliates/{id}', [AffiliateController::class, 'show']);
        Route::delete('/affiliates/{id}', [AffiliateController::class, 'destroy']);
        Route::get('/affiliates/{id}/downlines', [AffiliateController::class, 'getDownlines']);
        Route::get('affiliates/{id}/commission-statistics', [AffiliateController::class,'getCommissionStatistics']);
        Route::get('/affiliates/{id}/get-statistics', [AffiliateController::class, 'getStatistics']);
        Route::post('/affiliates/{id}/coupon', [AffiliateController::class, 'assignCoupon']);
        Route::post('/affiliates/{id}/set-parent', [AffiliateController::class, 'setParent']);
        Route::get('/affiliates/{id}/network-statistics', [AffiliateController::class, 'getNetworkStatistics']);
        Route::get('/affiliates/{id}/login-as', [AffiliateController::class, 'LoginAs']);
        Route::post('affiliates/{id}/change-group', [AffiliateController::class, 'changeGroup']);
        Route::post('affiliates/{id}/remove-group', [AffiliateController::class, 'removeGroup']);
        Route::post('affiliates/{id}/set-primary-aff-link', [AffiliateController::class, 'setPrimaryAffLink']);
        Route::post('affiliates/{id}/set-custom-aff-link', [AffiliateController::class, 'setCustomAffLink']);
        Route::put('affiliates/{id}/update-custom-aff-link', [AffiliateController::class, 'updateCustomAffLink']);
        Route::post('/affiliates/{id}/update-profile',[AffiliateController::class, 'updateProfile']);
        Route::post('/affliates/{id}/update-password',[AffiliateController::class, 'updatePassword']);
        Route::post('/affliates/{id}/update-payment-method',[AffiliateController::class, 'updatePaymentMethod']);

        //Product Commission
        Route::get('/product-commission', [ProductCommissionController::class, 'index']);
        Route::post('/product-commission', [ProductCommissionController::class, 'store']);
        Route::post('/product-commission/bulk-delete', [ProductCommissionController::class, 'bulkDelete']);
        Route::get('/product-commission/search-shopify-product', [ProductCommissionController::class, 'searchShopifyProduct']);
        Route::get('/product-commission/search-shopify-collection', [ProductCommissionController::class, 'searchShopifyCollection']);
        Route::put('/product-commission/{id}', [ProductCommissionController::class, 'update']);
        Route::delete('/product-commission/{id}', [ProductCommissionController::class, 'destroy']);

        //Setting
        Route::get('/settings/payment-methods', [SettingController::class, 'getPayment']);
        Route::post('/settings/payment-methods', [SettingController::class, 'storePaymentMethod']);
        Route::put('/settings/payment-methods', [SettingController::class, 'updatePaymentMethod']);
        Route::delete('/settings/payment-methods/{id}', [SettingController::class, 'deletePaymentMethod']);
        Route::post('/settings/payment-methods/{id}/status-toggle', [SettingController::class, 'toggleStatusPaymentMethods']);
        Route::get('/settings/check-fromemail-verified', [SettingController::class, 'checkFromEmailVerified']);
        Route::put('/shopsettings',[SettingController::class,'updateShopSetting']);
        Route::put('/shopsettings/updateContactEmail',[SettingController::class,'updateContactEmail']);
        Route::put('/shopsettings/updateFromEmail',[SettingController::class,'updateFromEmail']);
        Route::get('/shopsettings',[SettingController::class,'getShopSetting']);
        Route::post('/shopsettings/uploadLogo',[SettingController::class,'uploadLogo']);
        Route::put('/shopsettings/update-default-from-email',[SettingController::class,'updateDefaultFromEmail']);
        Route::post('/settings/create-embedded-portal', [SettingController::class, 'createEmbeddedPortal']);
        Route::get('/settings/get-page', [SettingController::class, 'getPage']);
        Route::get('/settings/get-coupon-automatic', [SettingController::class, 'getCouponAutomatic']);
        Route::post('/settings/settings-automatic-coupon', [SettingController::class,'settingsAutomaticCoupon']);
        Route::put('/settings/update-coupon-tracking', [SettingController::class,'updateCouponTracking']);
        Route::put('/settings/update-your-notifications', [SettingController::class,'updateYourNotifications']);
        Route::put('/settings/skip_started', [SettingController::class,'skipStarted']);
        Route::put('/settings/done_started', [SettingController::class,'doneStarted']);
        Route::put('/settings/default-affiliate-link',[SettingController::class,'updateDefaultLink']);
        Route::post('/settings/upload-post-checkout',[SettingController::class,'uploadImagePostCheckout']);
        Route::put('/settings/update-post-checkout',[SettingController::class,'updateCheckoutPopupData']);
        Route::put('/settings/active-popup-checkout',[SettingController::class,'activePopupCheckout']);
        Route::put('/settings/term_policy',[SettingController::class,'updateTermAndPolicy']);
        Route::get('/settings/term_policy',[SettingController::class,'getTermAndPolicy']);
        Route::get('/settings/sample_term_policy',[SettingController::class,'getSampleTermPolicy']);
        Route::get('/settings/affiliate-language',[SettingController::class,'getAffiliateLanguage']);
        Route::put('/settings/affiliate-language',[SettingController::class,'updateAffiliateLanguage']);
        Route::get('/settings/translations/{locale}',[SettingController::class,'getTranslations']);
        Route::put('/settings/translations/{locale}',[SettingController::class,'updateTranslations']);
        Route::put('/settings/self-generating-coupon',[SettingController::class,'settingSelfGeneratingCoupon']);
        Route::delete('/settings/automatic-coupon',[SettingController::class,'deleteAutomaticCoupon']);
        Route::get('/settings/system-notifications',[SettingController::class,'getSystemNotifications']);
        Route::get('/settings/system-notifications/load-more',[SettingController::class,'loadMoreSystemNotifications']);
        Route::put('/setting/login-form-config',[SettingController::class,'updateLoginFormConfig']);
        Route::get('/setting/login-form-config',[SettingController::class,'getLoginFormConfig']);

        //Payout
        Route::get('/payouts/pending-payments', [PayoutController::class, 'getPendingPayment']);
        Route::get('/payouts/paid-payments', [PayoutController::class, 'getPaidPayment']);
        Route::post('/payouts/single-payout', [PayoutController::class, 'singlePayout']);
        Route::post('/payouts/undo', [PayoutController::class, 'undoPayout']);
        Route::get('/payouts/{id}/orders', [PayoutController::class, 'getPaymentOrder']);
        Route::post('/payouts/store-credit', [PayoutController::class, 'storeCredit']);
        Route::post('/payouts/add-credit', [PayoutController::class, 'addCredit']);

        //Coupon
        Route::get('/coupons', [AffiliateCouponController::class, 'getListCoupons']);
        Route::post('/coupons', [AffiliateCouponController::class, 'assignCoupon']);
        Route::delete('/coupons/{removeInShopify}/{id}', [AffiliateCouponController::class, 'deleteCoupon']);

        //Email Template

        Route::post('/emailtemplates',[EmailTemplateController::class,'store']);
        Route::get('/emailtemplates/getEmailTemplateStatusByShopId',[EmailTemplateController::class,'getEmailTemplateStatusByShopId']);
        Route::get('/emailtemplates/{slug}',[EmailTemplateController::class,'getEmailTemplate']);
        Route::put('/emailtemplates',[EmailTemplateController::class,'changeStatusEmailTemplate']);
        Route::post('emailtemplates/bulk-email',[EmailTemplateController::class,'bulkEmail']);
        Route::get('mail-outbox',[EmailTemplateController::class,'getMailOutbox']);
        Route::delete('mail-outbox/{id}',[EmailTemplateController::class,'deleteMailOutbox']);
        Route::post('emailtemplates/upload-email-imgage',[EmailTemplateController::class,'uploadEmailImage']);


        //Category
        Route::get('/categorys/search', [CategoryController::class, 'search']);
        Route::post('/categorys/upload-file', [CategoryController::class, 'uploadFile']);
        Route::post('/categorys/create-creative', [CategoryController::class, 'createCreative']);
        Route::get('/categorys/get-category', [CategoryController::class, 'getCategory']);
        Route::get('/categorys/get-all-file', [CategoryController::class, 'getAllFile']);
        Route::get('/categorys/get-file-category/{id}', [CategoryController::class, 'getFileCategory']);
        Route::delete('/categorys/files/{id}', [CategoryController::class, 'deleteFile']);
        Route::put('/categorys/update-creative/{id}', [CategoryController::class, 'updateFile']);
        Route::delete('/categorys/{removeAllFile}/{id}', [CategoryController::class, 'deleteCategory']);
        Route::post('/categorys/bulk-upload',[CategoryController::class,'bulkUpload']);
        Route::post('categorys/image-editor',[CategoryController::class,'uploadImageEditor']);

        //AffiliateTier
        Route::get('/affiliate-tier',[AffiliateTierController::class, 'index']);
        Route::put('/affiliate-tier',[AffiliateTierController::class, 'updateAffiliateTier']);

        //Integration
        Route::put('/klaviyo/toggle_sync_klaviyo_approved_affiliate',[IntegrationController::class,'toggleSyncKlaviyoApprovedAffiliate']);
        Route::get('/klaviyo/list_klaviyo',[IntegrationController::class,'getListKlaviyo']);
        Route::post('/klaviyo/save_sync_klaviyo',[IntegrationController::class,'saveSyncKlaviyo']);
        Route::put('/klaviyo/toggle_klaviyo_sync_enabled',[IntegrationController::class,'toggleKlaviyoSyncEnabled']);
        Route::put('/klaviyo/sync_klaviyo',[IntegrationController::class,'syncKlaviyo']);

        Route::get('/user/profile', function () {
            // Uses first & second middleware...
        });

        //FeedBack

        Route::post('/feedback', [FeedBackController::class, 'store']);

    });
        Route::post('/bg_track', [ApiController::class, 'trackEvent']);
        Route::get('/automatic-coupon-customer',[GroupController::class, 'getAutomaticCouponCustomer']);

