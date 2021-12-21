<?php

namespace App\Http\Controllers\Affiliates\Auth;

use App\Events\AddAffiliateEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DoubleC\LaravelShopify\Traits\Authenticator;
use App\Models\Affiliate;
use App\Models\ShopSetting;
use App\Models\Group;
use App\Models\ShopInfo;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Events\AffiliateRegisteredEvent;
use App\Events\NewAffiliateEvent;
use App\Models\Shop;
use DB;
use Illuminate\Support\Str;
use App\Services\AffiliateCouponService;
use App\Models\AffiliateCoupon;

class RegisterController extends Controller
{
    use ApiResponser;

    public function getRegister(Request $request)
    {
        $responseData = [];
        $requestData = $request->all();
        $origin = $request->header('origin');
        $subdomain = getSubdomainFromUrl($origin);
        $setting = ShopSetting::where('sub_domain', $subdomain)->whereHas('shop', function ($query) {
            $query->whereNotNull('access_token');
        })->with('info:shop_id,currency,money_format')->first();
        if($setting) {
            $shopId = $setting->shop_id;

            if( $request->has('slug') ) {
                $group = Group::where('slug', $requestData['slug'])->where('is_active', 1)->where('is_private', 0)->first();
                $responseData['group'] = $group;
            }else{
                $group = Group::where('is_default', 1)->where('shop_id', $setting->shop_id)->where('is_active', 1)->where('is_private', 0)->first();
            }

            if($group) {
                $group->registration_form = synConfigUpdateRegistrationData($group->registration_form);
                $responseData['group'] = $group;
                $responseData['logo'] = $setting->logo;
                $responseData['brand_name'] = $setting->brand_name;
                $responseData['money_format'] = $setting->info->money_format;
                $responseData['term'] = $setting->term;
                $responseData['policy'] = $setting->policy;
                $responseData['is_enable_term_policy'] = $setting->is_enable_term_policy;
                $responseData['default_affiliate_language'] = $setting->default_affiliate_language;
                $responseData['enable_affiliate_language'] = $setting->enable_affiliate_language;
                $responseData['auto_detect_language'] = $setting->auto_detect_language;
            }else{
                return $this->errorResponse('group not found', 404);
            }
            return $this->successResponse($responseData, 'success', 200);

        }else{
            return $this->errorResponse('not found', 404);
        }
    }

    protected function validator(array $data, $shopId)
    {

        $rules = [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($shopId) {
                    $checkAffiliateExist = DB::table('affiliates')->where([
                        'shop_id' => $shopId,
                        'email' => $value
                    ])->get();
                    if ($checkAffiliateExist->count() != 0) {
                        $fail(trans('auth.email_exist'));

                    }
                }
            ],
            'first_name' => ['required','string', 'max:15'],
            'last_name' => ['required','string', 'max:15'],
            'password' => 'required|string|min:6|confirmed',
        ];

        if(isset($data['instagram'])) {
            $rules['instagram'] = 'max:400';
        }
        if(isset($data['youtube'])) {
            $rules['youtube'] = 'max:400';
        }
        if(isset($data['facebook'])) {
            $rules['facebook'] = 'max:400';
        }
        if(isset($data['twitter'])) {
            $rules['twitter'] = 'max:400';
        }
        if(isset($data['coupon_code'])) {
            $rules['coupon_code'] = 'max:100';
        }
        return Validator::make($data, $rules);
    }

    public function register(Request $request)
    {


        $data = $request->all();
        $subdomain = getSubdomainFromUrl($request->header('origin'));
        $shopSetting = ShopSetting::where('sub_domain', $subdomain)->first();
        if(!$shopSetting) {
            info($subdomain);
            return response()->json([
                'message' => 'sub domain is not found, please check the link again'
            ], 404);
        }
        \Illuminate\Support\Facades\App::setLocale($shopSetting->default_affiliate_language);
        $shopInfor=ShopInfo::where('shop_id',$shopSetting->shop_id)->select('domain')->first();

        $group = Group::findOrFail($data['group']);

        $this->validator($data, $shopSetting->shop_id)->validate();

        $data['shop_id'] = $shopSetting->shop_id;
        $data['source'] = 'signup';
        if( $request->has('parent') ) {
            $parent = Affiliate::where('hash_code', $request->get('parent'))->first();
            if($parent) {
                $data['parent_id'] = $parent->id;

            }

        }else{
            $data['parent_id'] = 0;
        }
        $group->auto_approve_affiliate ? $data['status'] = 1 : $data['status'] = 2;



        $affiliate = $this->create($data);
        event(new AffiliateRegisteredEvent($affiliate));
        event(new NewAffiliateEvent($affiliate));

        $token = auth('affiliate-api')->login($affiliate);
        return response()->json([
            'affiliate' => $affiliate,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);

    }

    /**
     * Create a new affiliate instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $newAffiliate = new Affiliate;
        $newAffiliate->email = $data['email'];
        $newAffiliate->first_name = $data['first_name'];
        $newAffiliate->last_name = $data['last_name'];
        $newAffiliate->password = Hash::make($data['password']);
        $newAffiliate->shop_id = $data['shop_id'];
        $newAffiliate->group_id = $data['group'];
        $newAffiliate->parent_id = $data['parent_id'];
        // $newAffiliate->primary_aff_link = $data['primary_aff_link'];
        $newAffiliate->hash_code = $this->checkExistAndGetRandomHashcode();
        $newAffiliate->status = $data['status'];
        $newAffiliate->source = isset($data['source']) ? $data['source'] : null;
        $newAffiliate->company = isset($data['company']) ? $data['company'] : null;
        $newAffiliate->address = isset($data['address']) ? $data['address'] : null;
        $newAffiliate->country = isset($data['country']) ? $data['country'] : null;
        $newAffiliate->city = isset($data['city']) ? $data['city'] : null;
        $newAffiliate->zipcode = isset($data['zipcode']) ? $data['zipcode'] : null;
        $newAffiliate->state = isset($data['state']) ? $data['state'] : null;
        $newAffiliate->phone = isset($data['phone']) ? $data['phone'] : null;
        $newAffiliate->facebook = isset($data['facebook']) ? $data['facebook'] : null;
        $newAffiliate->youtube = isset($data['youtube']) ? $data['youtube'] : null;
        $newAffiliate->instagram = isset($data['instagram']) ? $data['instagram'] : null;
        $newAffiliate->twitter = isset($data['twitter']) ? $data['twitter'] : null;
        $newAffiliate->website = isset($data['website']) ? $data['website'] : null;
        $newAffiliate->personal_detail = isset($data['personal_detail']) ? $data['personal_detail'] : null;
        $newAffiliate->additional_infos = $this->getAdditionalInfos($data, $data['group']);
        $newAffiliate->save();
        return $newAffiliate;
    }

    private function getAdditionalInfos($data, $groupId)
    {
        $group = Group::where('id', $groupId)->select('registration_form')->first();
        $additionalInfos = [];
        foreach($data as $k=>$v) {
            if(strpos($k, 'ext_') !== false){
                $additionalInfos[] = [
                    'label' => $this->getLabelAdditionalInfo($group->registration_form->schema->fields, $k),
                    'value' => $v
                ];
            }
        }
        if(empty($additionalInfos)) {
            foreach($group->registration_form->schema->fields as $f){
                if(strpos($f->model, 'ext_') !== false && $f->visible){
                    $additionalInfos[] = [
                        'label' => $f->label,
                        'value' => '',
                        'model' => $f->model
                    ];
                }
            }
        }
        if(empty($additionalInfos)) {
            return null;
        }

        return $additionalInfos;
    }

    private function getLabelAdditionalInfo($fields, $model)
    {
        foreach($fields as $f) {
            if($f->model == $model){
                return $f->label;
            }
        }
    }

    private function checkExistAndGetRandomHashcode()
    {
        while(1) {
            $hashcode = generateRandomString();
            $check = Affiliate::where('hash_code', $hashcode)->first();
            if( !$check ) {
                break;
            }
        }
        return $hashcode;
    }

    public function checkoutPopupRegister(Request $request)
    {
        $shop = Shop::where('shop',$request['shop'])->with('info:shop_id,domain')->first();
        $shopId = $shop->id;
        $shopSetting = ShopSetting::where('shop_id',$shopId)->
        select('checkout_popup_data','auto_generate_coupon','price_rule','default_affiliate_link')->first();
        $data = [];
        $data['parent_id'] = 0;
        $data['shop_id'] = $shopId;
        $data['group'] = $shopSetting->checkout_popup_data->program;
        $data['status'] = 1;
        $data['first_name'] = $request['first_name'];
        $data['last_name'] = $request['last_name'];
        $data['password'] = generateRandomString(6);
        $data['email'] = $request['email'];
        $data['password_confirmation'] =  $data['password'];
        $data['source'] = 'popup';
        if($this->validator($data,$shopId)->fails()){
            return response()->json(null);
         }
        $affiliate = $this->create($data);
        $couponCode = '';
        if($shopSetting->auto_generate_coupon && $shopSetting->price_rule)
        {
            $priceRule =  $shopSetting->price_rule;
            $priceRuleId = $priceRule['price_rule'];
            if($priceRule['coupon_style']=='name')
            {
                $couponCode = couponCodeFromAffiliateName($affiliate->first_name . $affiliate->last_name) ;
            }
            else{
                $couponCode= strtoupper(Str::random(10));
            }
            $shopName = shopNameFromDomain($shop->shop);
            $checkAffiliateExist = AffiliateCoupon::where([
                'shop_id' =>  $shop->id,
                'code' =>$couponCode
            ])->get();
            if(!count($checkAffiliateExist))
            {
                $couponService=new AffiliateCouponService;
               $couponCode = $couponService->createCouponCode($shopName,$shop,$couponCode,$priceRuleId,$affiliate );
            }
            else{
                $couponCode = $couponCode.$this->checkCouponExistAndGetRandomHashcode($couponCode,$shopId);
                $couponService=new AffiliateCouponService;
               $couponCode = $couponService->createCouponCode($shopName,$shop,$couponCode,$priceRuleId,$affiliate);
            }
        }
        event(new AddAffiliateEvent($affiliate,$data['password']));
        return response()->json([
            'ref_code'=> $affiliate->hash_code,
            'reflink' => $this->generateLinkAffiliate($shopSetting->default_affiliate_link).$affiliate->group_id.':'.$affiliate->hash_code,
            'couponCode' => $couponCode,
            'sharing_message' => $shopSetting->checkout_popup_data->sharringMsg
        ]);
    }

    public function isShowPopupRegister(Request $request)
    {
        $shop = Shop::where('shop',$request['shop'])->with('info:shop_id,domain')->first();
        info('Checkout popup -- Request: '.$request['shop']);
        $shopId = $shop->id;
        $emailExist = Affiliate::where('shop_id',$shopId)->where('email',$request['email'])->get();
        if($emailExist->count()!=0){
            return response()->json(null);
         }
        return response()->json([
            'status'=> 'success'
        ]);
    }

    private function checkCouponExistAndGetRandomHashcode($code,$shopId)
    {
        while(1) {
            $hashcode = rand(1,100);
            $check = AffiliateCoupon::where('code',$code.$hashcode)->where('shop_id',$shopId)->first();
            if( !$check ) {
                break;
            }
        }
        return $hashcode;
    }


    public function generateLinkAffiliate($link)
    {
        if($link)
        {
            $arrLinkSplit = explode('?',$link);
            if(count($arrLinkSplit)>1 && $arrLinkSplit[1] !== '')
            {
                return $link . "&bg_ref=";
            }
            else{
                return $link . "?bg_ref=";
            }
        }
        else{
            return '';
        }
    }

}
