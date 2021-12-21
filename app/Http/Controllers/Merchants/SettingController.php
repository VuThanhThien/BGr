<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Shop;
use App\Models\ShopInfo;
use App\Models\ShopSetting;
use Illuminate\Http\Request;
use App\Services\SettingService;

class SettingController extends Controller
{

    private SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function getPayment(Request $request)
    {
        $paymentMethods = $this->settingService->getPaymentMethods();
        return $this->successResponse($paymentMethods, 'success', 200);
    }

    public function toggleStatusPaymentMethods($id)
    {
        $this->settingService->toggleStatusPaymentMethods($id);
        return $this->successResponse('', 'success', 200);
    }

    public function storePaymentMethod(Request $request)
    {
        $method = $this->settingService->storePaymentMethod($request->all());
        return $this->successResponse($method, 'success', 200);
    }

    public function updatePaymentMethod(Request $request)
    {
        $method = $this->settingService->updatePaymentMethod($request->all());
        return $this->successResponse($method, 'success', 200);
    }

    public function deletePaymentMethod($id)
    {
        $method = $this->settingService->deletePaymentMethod($id);
        return $this->successResponse('', 'success', 200);
    }

    public function checkFromEmailVerified()
    {
        $res = $this->settingService->checkFromEmailVerified();
        return $this->successResponse($res, 'success', 200);
    }
    //
    public function updateShopSetting(Request $request)
    {
        $shopSetting=$this->settingService->updateShopSetting($request->all());
        return $this->successResponse($shopSetting,'success',200);
    }

    public function updateContactEmail(Request $request)
    {
        $shopSetting=$this->settingService->updateContactEmail($request->only('contact_name','contact_email'));
        return $this->successResponse($shopSetting,'success',200);
    }

    public function updateFromEmail(Request $request)
    {
        $shopSetting=$this->settingService->updateFromEmail($request->only('from_contact_name','from_contact_email'));
        if($shopSetting) {
            return $this->successResponse($shopSetting,'success',200);
        }else{
            return $this->errorResponse('amazon service error', 500, [] );
        }

    }

    public function getShopSetting(){
        $shopSetting=$this->settingService->getShopSetting();
        return $this->successResponse($shopSetting,'success',200);
    }

    public function uploadLogo(Request $request)
    {
        $pathLogo=$this->settingService->uploadLogo($request->logo,$request->file('logo'));
        return $this->successResponse($pathLogo,'success',200);
    }

    public function updateDefaultFromEmail()
    {
        $shopSetting=$this->settingService->updateDefaultFromEmail();
        return $this->successResponse($shopSetting,'success',200);
    }

    public function createEmbeddedPortal(Request $request){
        $result= $this->settingService->createEmbeddedPortal($request->all());
        return $this->successResponse($result,'success',200);
    }

    public function getPage(){
        $result= $this->settingService->getPage();
        return $this->successResponse($result,'success',200);
    }

    public function getCouponAutomatic(){
        $result= $this->settingService->getCouponAutomatic();
        return $this->successResponse($result,'success',200);
    }

    public function settingsAutomaticCoupon(Request $request){
        $result= $this->settingService->settingsAutomaticCoupon($request->all());
        return $this->successResponse($result,'success',200);
    }

    public function updateCouponTracking(Request $request)
    {
        $result= $this->settingService->updateCouponTracking($request->only('type','status'));
        return $this->successResponse($result,'success',200);
    }

    public function senderEmailVerifySuccess(Request $request)
    {
        return view('sender_email_verify_success');
    }

    public function senderEmailVerifyFail(Request $request)
    {
        return view('sender_email_verify_fail');
    }

    public function updateYourNotifications(Request $request)
    {
        $result= $this->settingService->updateYourNotifications($request->only('type','status'));
        return $this->successResponse($result,'success',200);
    }

    public function skipStarted(Request $request)
    {
        $result= $this->settingService->skipStarted();
        return $this->successResponse('','success',200);
    }

    public function doneStarted(Request $request)
    {
        $result= $this->settingService->doneStarted();
        return $this->successResponse('','success',200);
    }

    public function updateDefaultLink(Request $request)
    {
        $shopSetting=$this->settingService->updateDefaultLink($request->only('default_affiliate_link','allow_affiliates_custom_link'));
        return $this->successResponse($shopSetting,'success',200);
    }

    public function getSettingPopup(Request $request)
    {
        $shopDomain = $request->get('shop');
        if(strpos($shopDomain, '.myshopify.com') !== false)
        {
            $shopId = Shop::where('shop',$shopDomain)->first()->id;
        }
        else{
            $shopId = ShopInfo::where('domain',$shopDomain)->first()->shop_id;
        }
        $shopSettings = ShopSetting::where('shop_id',$shopId)->select('shop_id','checkout_popup_data','sub_domain')->with('info:shop_id,domain,money_format')->first();
        if(is_array($shopSettings->checkout_popup_data))
        {
            $shopSettings->checkout_popup_data  = json_decode(json_encode(config('myconfig.checkoutpopupdata')));
        }
        $program = $shopSettings->checkout_popup_data->program;
        $checkoutPopupDataTemp = $shopSettings->checkout_popup_data;
        if($program)
        {
             $group = Group::where('shop_id',$shopId)->where('id',$program)->select('id','commission_amount','commission_type')->first();
             $tags=[
                '{commission_amount}'=>  formatCommissionAmount($group->commission_type, $group->commission_amount,$shopSettings->info->money_format)
             ];
             $textContent3 = $checkoutPopupDataTemp->textContent3;
             $checkoutPopupDataTemp->textContent3 =  $this->replacesTags($tags, $textContent3) ;
        }
        if(empty($checkoutPopupDataTemp->onClickLink))
        {
           $checkoutPopupDataTemp->onClickLink = 'https://' . $shopSettings->sub_domain . '.' . config('endpoint.main_domain') . '/#/register';
        }
        $shopSettings->checkout_popup_data =  $checkoutPopupDataTemp;
        $content = view('script_tag_checkout_popup',['shopSettings'=>$shopSettings])->render();
        return $content;
    }

    public function uploadImagePostCheckout(Request $request)
    {
        $srcImg=$this->settingService->uploadImagePostCheckout($request->image,$request->file('image'));
        return $this->successResponse($srcImg,'success',200);
    }

    public function updateCheckoutPopupData(Request $request)
    {
        $res=$this->settingService->updateCheckoutPopupData($request->all());
        return $this->successResponse($res,'success',200);
    }

    public function activePopupCheckout(Request $request)
    {
        $res=$this->settingService->activePopupCheckout($request->all());
        return $this->successResponse($res,'success',200);
    }

    protected function replacesTags($tags,$content)
    {
        foreach($tags as $key=> $value){
            $content = str_replace($key,$value,$content);
        }
        return $content;
    }

    public function updateTermAndPolicy(Request $request)
    {
        $res = $this->settingService->updateTermAndPolicy($request->all());
        return $this->successResponse($res,'success',200);
    }

    public function getTermAndPolicy()
    {
        $res = $this->settingService->getTermAndPolicy();
        return $this->successResponse($res,'success',200);
    }

    public function getSampleTermPolicy()
    {
        $res = $this->settingService->getSampleTermPolicy();
        return $this->successResponse($res,'success',200);
    }

    public function getAffiliateLanguage()
    {
        $res = $this->settingService->getAffiliateLanguage();
        return $this->successResponse($res,'success',200);
    }

    public function updateAffiliateLanguage(Request $request)
    {
        $res = $this->settingService->updateAffiliateLanguage($request->only('default_affiliate_language','enable_affiliate_language','auto_detect_language'));
        return $this->successResponse($res,'success',200);
    }

    public function getTranslations(Request $request,$locale)
    {
        $res = $this->settingService->getTranslations($request->all(),$locale);
        return $this->successResponse($res,'success',200);
    }

    public function updateTranslations(Request $request,$locale)
    {
        $res = $this->settingService->updateTranslations($request->all(),$locale);
        return $this->successResponse($res,'success',200);
    }

    public function settingSelfGeneratingCoupon(Request $request)
    {
        $this->settingService->settingSelfGeneratingCoupon($request->only('autopay_discount_code','allow_self_generating_coupon'));
        return $this->successResponse(true, 'success', 200);
    }

    public function deleteAutomaticCoupon()
    {
        $res = $this->settingService->deleteAutomaticCoupon();
        return $this->successResponse($res,'success',200);
    }

    public function getSystemNotifications()
    {
        $res = $this->settingService->getSystemNotifications();
        return $this->successResponse($res,'success',200);
    }

    public function loadMoreSystemNotifications()
    {
        $res = $this->settingService->loadMoreSystemNotifications();
        return $this->successResponse($res,'success',200);
    }

    public function updateLoginFormConfig(Request $request)
    {
        $res = $this->settingService->updateLoginFormConfig($request->all());
        return $this->successResponse($res,'success',200);
    }

    public function getLoginFormConfig()
    {
        $res = $this->settingService->getLoginFormConfig();
        return $this->successResponse($res,'success',200);
    }
}
