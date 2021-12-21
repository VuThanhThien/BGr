<?php

namespace App\Http\Controllers\Merchants\Auth;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\Group;
use App\Models\ShopInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Events\AddAffiliateEvent;
use App\Events\NewAffiliateEvent;
use App\Models\AffiliateCoupon;
use App\Models\Shop;
use App\Models\ShopSetting;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Str;
use App\Services\AffiliateCouponService;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $shopId = auth()->user()->shop_id;
        $shop = Shop::find($shopId);
        $data = $request->all();
        $this->validator($data, $shopId)->validate();
        $shopSetting = ShopSetting::where('shop_id', $shopId)->select('auto_generate_coupon', 'price_rule')->first();
        $data['shop_id'] = $shopId;
        $data['parent_id'] = 0;
        $data['status'] = 1;
        $data['source'] = 'add';
        if (!$data['passwordType']) {
            $data['password'] = generateRandomString(6);
        }
        $affiliate = $this->create($data);
        if ($data['isSendNotification']) {
            event(new AddAffiliateEvent($affiliate, $data['password']));
        }
        if ($shopSetting->auto_generate_coupon && $shopSetting->price_rule) {
            $priceRule =  $shopSetting->price_rule;
            $priceRuleId = $priceRule['price_rule'];
            if ($priceRule['coupon_style'] == 'name') {
                $couponCode = couponCodeFromAffiliateName($affiliate->first_name . $affiliate->last_name);
            } else {
                $couponCode = strtoupper(Str::random(10));
            }
            $shopName = shopNameFromDomain($shop->shop);
            $checkAffiliateExist = AffiliateCoupon::where([
                'shop_id' =>  $shop->id,
                'code' => $couponCode
            ])->get();
            if (!count($checkAffiliateExist)) {
                $couponService = new AffiliateCouponService;
                $couponCode = $couponService->createCouponCode($shopName, $shop, $couponCode, $priceRuleId, $affiliate);
            } else {
                $couponCode = $couponCode . $this->checkCouponExistAndGetRandomHashcode($couponCode,$shopId);
                $couponService = new AffiliateCouponService;
                $couponCode = $couponService->createCouponCode($shopName, $shop, $couponCode, $priceRuleId, $affiliate);
            }
        }
        return $this->successResponse(true, 'success', 200);
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
                        $fail('email existed');
                    }
                }
            ],
            'firstName' => ['required', 'string', 'max:15'],
            'lastName' => ['required', 'string', 'max:15'],
            'password' => 'required|string|min:6',
        ];
        return Validator::make($data, $rules);
    }
    protected function create(array $data)
    {
        $newAffiliate = new Affiliate;
        $newAffiliate->email = $data['email'];
        $newAffiliate->first_name = $data['firstName'];
        $newAffiliate->last_name = $data['lastName'];
        $newAffiliate->password = Hash::make($data['password']);
        $newAffiliate->shop_id = $data['shop_id'];
        $newAffiliate->group_id = $data['groupSelected'];
        $newAffiliate->parent_id = $data['parent_id'];
        $newAffiliate->primary_aff_link = null;
        $newAffiliate->hash_code = $this->checkExistAndGetRandomHashcode();
        $newAffiliate->status = $data['status'];
        $newAffiliate->source = isset($data['source']) ? $data['source'] : null;
        $newAffiliate->company = isset($data['company']) ? $data['company'] : null;
        $newAffiliate->address = isset($data['address']) ? $data['address'] : null;
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
        $newAffiliate->save();
        return $newAffiliate;
    }
    private function checkExistAndGetRandomHashcode()
    {
        while (1) {
            $hashcode = generateRandomString();
            $check = Affiliate::where('hash_code', $hashcode)->first();
            if (!$check) {
                break;
            }
        }
        return $hashcode;
    }
    public function importAffiliate(Request $request)
    {
        $shopId = auth()->user()->shop_id;
        $shop = Shop::find($shopId);
        $shopSetting = ShopSetting::where('shop_id',$shopId)->select('auto_generate_coupon', 'price_rule')->first();
        $data = $request->all();
        $arrAffiliateError = [];
        $rowMax = 0;
        if (isset($data['pathExcel'])) {
            $users = (new FastExcel)->configureCsv(',')->import($data['pathExcel'], function ($line) use ($data,$shop,$shopSetting, $shopId, &$rowMax, &$arrAffiliateError) {
                if ($rowMax >= 250) {
                    return false;
                }
                if ($rowMax == 0 && (!isset($line['first_name']) || !isset($line['last_name'])) || !isset($line['email'])) {
                    $errorField = [];
                    if (!isset($line['first_name'])) {
                        array_push($errorField, "first_name");
                    }
                    if (!isset($line['last_name'])) {
                        array_push($errorField, "last_name");
                    }
                    if (!isset($line['email'])) {
                        array_push($errorField, "email");
                    }
                    $messError = 'The field ' . join(",", $errorField) . ' must be included in your file.';
                    array_push($arrAffiliateError, $messError);
                    $rowMax = 250;
                    return false;
                }
                $rowMax++;
                $data['firstName'] = isset($line['first_name']) ? $line['first_name'] : null;
                $data['lastName'] = isset($line['last_name']) ? $line['last_name'] : null;
                $data['email'] = isset($line['email']) ? $line['email'] : null;
                $data['country'] = isset($line['country']) ? $line['country'] : null;
                $data['company'] = isset($line['company']) ? $line['company'] : null;
                $data['address'] = isset($line['address']) ? $line['address'] : null;
                $data['city'] = isset($line['city']) ? $line['city'] : null;
                $data['zipcode'] = isset($line['zipcode']) ? $line['zipcode'] : null;
                $data['state'] = isset($line['state']) ? $line['state'] : null;
                $data['phone'] = isset($line['phone']) ? $line['phone'] : null;
                $data['facebook'] = isset($line['facebook']) ? $line['facebook'] : null;
                $data['youtube'] = isset($line['youtube']) ? $line['youtube'] : null;
                $data['instagram'] = isset($line['instagram']) ? $line['instagram'] : null;
                $data['twitter'] = isset($line['twitter']) ? $line['twitter'] : null;
                $data['website'] = isset($line['website']) ? $line['website'] : null;
                $data['personal_detail'] = isset($line['personal_detail']) ? $line['personal_detail'] : null;
                $data['source'] = 'import';
                if ($this->validator($data, $shopId)->fails()) {
                    array_push($arrAffiliateError, isset($line['email']) ? $line['email'] : 'null');
                    return true;
                } else {
                    $data['shop_id'] = $shopId;
                    $data['primary_aff_link'] = null;
                    $data['parent_id'] = 0;
                    $data['status'] = 1;
                    if (!$data['passwordType']) {
                        $data['password'] = generateRandomString(6);
                    }
                    if (isset($line['password'])) {
                        $data['password'] = $line['password'];
                    }
                    $affiliate = $this->create($data);
                    if ($data['isSendNotification']) {
                        event(new AddAffiliateEvent($affiliate, $data['password']));
                    }
                    if ($shopSetting->auto_generate_coupon && $shopSetting->price_rule) {
                        $priceRule =  $shopSetting->price_rule;
                        $priceRuleId = $priceRule['price_rule'];
                        if ($priceRule['coupon_style'] == 'name') {
                            $couponCode = couponCodeFromAffiliateName($affiliate->first_name . $affiliate->last_name);
                        } else {
                            $couponCode = strtoupper(Str::random(10));
                        }
                        $shopName = shopNameFromDomain($shop->shop);
                        $checkAffiliateExist = AffiliateCoupon::where([
                            'shop_id' =>  $shop->id,
                            'code' => $couponCode
                        ])->get();
                        if (!count($checkAffiliateExist)) {
                            $couponService = new AffiliateCouponService;
                            $couponCode = $couponService->createCouponCode($shopName, $shop, $couponCode, $priceRuleId, $affiliate);
                        } else {
                            $couponCode = $couponCode . $this->checkCouponExistAndGetRandomHashcode($couponCode,$shopId);
                            $couponService = new AffiliateCouponService;
                            $couponCode = $couponService->createCouponCode($shopName, $shop, $couponCode, $priceRuleId, $affiliate);
                        }
                    }
                    return true;
                }
            });
            Storage::delete($data['path']);
            return $this->successResponse($arrAffiliateError, 'success', 200);
        } else {
            return $this->successResponse(null, 'success', 200);
        }
    }

    private function checkCouponExistAndGetRandomHashcode($code,$shopId)
    {
        while (1) {
            $hashcode = rand(1, 100);
            $check = AffiliateCoupon::where('code', $code . $hashcode)->where('shop_id',$shopId)->first();
            if (!$check) {
                break;
            }
        }
        return $hashcode;
    }
}
