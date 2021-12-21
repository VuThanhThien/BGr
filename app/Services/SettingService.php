<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Group;
use App\Models\Shop;
use App\Models\ShopInfo;
use App\Models\PaymentMethod;
use App\Models\ShopPaymentMethod;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\ShopSetting;
use App\Models\Translate;
use Carbon\Carbon;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\Theme;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\Asset;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\Page;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use Exception;
use Illuminate\Support\Facades\Validator;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Discounts\DiscountCode;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Discounts\PriceRule;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Events\Webhook;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\ScriptTag;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    public function getPaymentMethods()
    {
        $shopId = Auth::user()->shop_id;
        $paymentMethods = PaymentMethod::where('payment_methods.shop_id', $shopId)->orWhere('payment_methods.shop_id', 0)
            ->leftJoin('shop_payment_methods', function ($join) use ($shopId) {
                $join->on('payment_methods.id', '=', 'shop_payment_methods.method_id');
                $join->where('shop_payment_methods.shop_id', '=', $shopId);
            })
            // ->leftJoin('shop_payment_methods', 'payment_methods.id', '=', 'shop_payment_methods.method_id')
            // ->where('shop_payment_methods.shop_id', $shopId)
            ->select('payment_methods.id', 'payment_methods.key', 'payment_methods.name', 'payment_methods.fields', 'payment_methods.type', 'shop_payment_methods.shop_id', DB::raw('(CASE WHEN shop_payment_methods.shop_id IS NULL THEN 0 ELSE 1 END) as status'))
            ->get();
        return $paymentMethods;
    }

    public function toggleStatusPaymentMethods($id)
    {
        $shopId = Auth::user()->shop_id;
        $res = ShopPaymentMethod::where('shop_id', $shopId)->where('method_id', $id)->first();
        if ($res) {
            $res->delete();
        } else {
            ShopPaymentMethod::create(['shop_id' => $shopId, 'method_id' => $id]);
        }
        return true;
    }

    private function checkExistMethodKey()
    {
        while (1) {
            $key = generateRandomString();
            $check = PaymentMethod::where('key', $key)->first();
            if (!$check) {
                break;
            }
        }
        return $key;
    }

    public function storePaymentMethod($data)
    {
        $shopId = Auth::user()->shop_id;
        $key = $this->checkExistMethodKey();
        $newMethod = new PaymentMethod;
        $newMethod->name = $data['method_name'];
        $newMethod->type = 1;
        $newMethod->key = $key;
        $newMethod->fields = $data['fields'];
        $newMethod->shop_id = $shopId;
        $newMethod->save();
        return $newMethod;
    }

    public function updatePaymentMethod($data)
    {
        $shopId = Auth::user()->shop_id;

        $updateMethod = PaymentMethod::findOrFail($data['id']);
        $updateMethod->name = $data['method_name'];
        $updateMethod->fields = $data['fields'];
        $updateMethod->save();
        return $updateMethod;
    }

    public function deletePaymentMethod($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        if (!auth()->user()->can('delete', $paymentMethod)) {
            throw new \Illuminate\Auth\Access\AuthorizationException();
        }
        Affiliate::where('shop_id', $paymentMethod->shop_id)->where('payment_method', $paymentMethod->key)->update(['payment_method' => NULL, 'payment_info' => NULL]);
        $paymentMethod->delete();

        return true;
    }

    public function checkFromEmailVerified()
    {
        $shopId = auth()->user()->shop_id;
        $shopSetting = ShopSetting::where('shop_id', $shopId)->select('id', 'from_contact_email', 'is_verified_from_email', 'from_contact_name')->first();
        if ($shopSetting->from_contact_email && !$shopSetting->is_verified_from_email) {
            $ses = new \App\Services\AmazonSES();
            $res = $ses->sendTestEmail($shopSetting->from_contact_email);
            if ($res) {
                $shopSetting->is_verified_from_email = 1;
                $shopSetting->save();
                return [
                    'verified' => 'verified',
                    'from_contact_email' => $shopSetting->from_contact_email,
                    'from_contact_name' => $shopSetting->from_contact_name,
                ];
            } else {
                return [
                    'verified' => 'pending',
                    'from_contact_email' => $shopSetting->from_contact_email,
                    'from_contact_name' => $shopSetting->from_contact_name,
                ];
            }
        }
        return [
            'verified' => 'verifiedDefault',
            'from_contact_email' => config('mail.from.address'),
            'from_contact_name' => $shopSetting->from_contact_name,
        ];
    }

    public function toogleOrderCreateWebhook($shopId, $action)
    {
        $shop = Shop::where('id', $shopId)->select('access_token', 'shop')->first();
        $shopName = shopNameFromDomain($shop->shop);
        $clientApi = new ClientApi($shopName, '', $shop->access_token);
        $webhook = new Webhook($clientApi);
        if($action == "create") {

            $webhook->create(['webhook' => [
                'topic' => 'orders/create',
                'address'   => route('webhook.order_created',['shop_id'=>$shopId]),
                'format'=> 'json'
            ]]);

        }else{
            $listWebhooks = $webhook->all()['webhooks'];
            foreach($listWebhooks as $h) {
                if($h['topic'] == "orders/create") {
                    $webhook->delete($h['id']);
                    break;
                }
            }
        }
    }

    public function updateShopSetting($data)
    {
        $shopId = auth()->user()->shop_id;
        Validator::make($data, [
            'sub_domain' => [
                'required',
                new \App\Rules\SubdomainUnique(),
                'max:255'
            ],
        ])->validate();
        $shopSetting = ShopSetting::where('shop_id', $shopId)->first();
        $shopSetting->brand_name = $data['brand_name'];
        $shopSetting->favicon = $data['favicon'];
        $shopSetting->sub_domain = $data['sub_domain'];




        if (!isset($data['logo'])) {
            if ($shopSetting->logo) {
                info(\Storage::disk('s3')->delete($shopSetting->logo));
            }
            $shopSetting->logo = null;
            $shopSetting->path_name = null;
        } else {
            if ($shopSetting->logo != $data['logo']) {
                $shopSetting->logo = $data['logo'];
                $shopSetting->path_name = $data['path_name'];
            }
        }

        if($shopSetting->is_record_pending_order != $data['is_record_pending_order']) {
            if($data['is_record_pending_order']) {
                $this->toogleOrderCreateWebhook($shopId, "create");
            }else{
                $this->toogleOrderCreateWebhook($shopId, "delete");
            }
        }

        $shopSetting->is_record_pending_order = $data['is_record_pending_order'];

        $shopSetting->save();
        return $shopSetting;
    }

    public function updateContactEmail($data)
    {
        $shopId = auth()->user()->shop_id;
        $shopSetting = ShopSetting::where('shop_id', $shopId)->first();
        $shopSetting->contact_name = $data['contact_name'];
        $shopSetting->contact_email = $data['contact_email'];
        $shopSetting->save();
        return $shopSetting;
    }

    public function sendVerifyAmazonEmail($email)
    {
        $ses = new \App\Services\AmazonSES();
        try {
            $ses->sendVerifyEmail($email, 'verifySenderEmail');
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function updateDefaultFromEmail()
    {
        $user = auth()->user();
        $shopId = $user->shop_id;
        $shopSetting = ShopSetting::where('shop_id', $shopId)->first();
        $shopSetting->from_contact_email = null;
        $shopSetting->is_verified_from_email = 0;
        $shopSetting->save();
        return [
            'shopSetting' => $shopSetting,
            'emailDefault' => config('mail.from.address')
        ];
    }

    public function updateFromEmail($data)
    {
        $user = auth()->user();
        $shopId = $user->shop_id;
        if ($this->sendVerifyAmazonEmail($data['from_contact_email'])) {
            $shopSetting = ShopSetting::where('shop_id', $shopId)->first();
            $shopSetting->from_contact_name = $data['from_contact_name'];
            $shopSetting->from_contact_email = $data['from_contact_email'];
            $shopSetting->is_verified_from_email = 0;
            $shopSetting->save();
        } else {
            return false;
        }
        return [
            "shopSetting" => $shopSetting,
            "emailDefault" => config('mail.from.address')
        ];
    }

    public function getShopSetting()
    {
        $shopId = auth()->user()->shop_id;
        $shopSetting = ShopSetting::where('shop_id', $shopId)->first();
        return $shopSetting;
    }

    public function uploadLogo($data, $file)
    {
        $fileNameOrigin = $data->getClientOriginalName();
        $fileNameHash = time() . '_' . generateRandomString(5);
        $filePath = $file->storePubliclyAs('logos', $fileNameHash, 's3');
        $filePathDB = Storage::disk('s3')->url($filePath);
        return [
            'path' => $filePathDB,
            'path_name' => $filePath
        ];
    }
    public function createEmbeddedPortal($data)
    {
        $validator = Validator::make($data, [
            'path' => array('required', 'regex:/^[a-zA-Z0-9\\-]+$/'),
        ]);
        $validator->validate();
        $shopId = Auth::user()->shop_id;
        $shop = Shop::findOrFail($shopId);
        $shopName = shopNameFromDomain($shop->shop);
        $shopSetting = $shop->shopSetting()->first();
        $subDomain = $shopSetting->sub_domain;
        $iframeUrl = 'https://' . $subDomain . '.' . config('endpoint.main_domain') . '/#/register';
        $groupNotDefault = null;
        if (isset($data['group_id'])) {
            $group = Group::find($data['group_id']);
            if (!$group->is_default) {
                $groupNotDefault = $group->id;
                $iframeUrl = 'https://' . $subDomain . '.' . config('endpoint.main_domain') . '/#/register/' . $group->slug;
            }
        }
        $clientApi = new ClientApi($shopName, '', $shop->access_token);
        if (!$data['isEnable']) {
            if ($data['page_id']) {
                $page = new Page($clientApi);
                $result = $page->delete($data['page_id']);
            }
            if (is_null($groupNotDefault)) {
                $settingsEmbed = [
                    'is_enable' => $data['isEnable'],
                    'is_fullScreen' => $data['isFullscreen'],
                    'portal_path' => $data['path'],
                    'page_id' => 0,
                    'css' => isset($data['css']) ? $data['css'] : '',
                    'group_id' => $data['group_id']
                ];
            } else {
                $settingsEmbed = [
                    'is_enable' => $data['isEnable'],
                    'is_fullScreen' => $data['isFullscreen'],
                    'portal_path' => $data['path'],
                    'page_id' => 0,
                    'css' => isset($data['css']) ? $data['css'] : '',
                    'group_id' => $groupNotDefault
                ];
            }

            $shopSetting->embedded_portal_settings = $settingsEmbed;
            $shopSetting->save();
            return [
                'status' => true,
                'message' => 'Settings updated!',
                'id' => 0
            ];
        }
        try {
            $theme = new Theme($clientApi);
            $header = '';
            $footer = '';
            if ($data['isFullscreen']) {
                $header = '{% layout none %}
                <!doctype html>
                <html lang="{{ shop.locale }}">
                <head>
                  <meta charset="utf-8">
                  <link rel="apple-touch-icon" sizes="180x180" href="{{ "favicon.png" | asset_url }}">
                  <link rel="icon" type="image/png" href="{{ "favicon.png" | asset_url }}" sizes="32x32">
                  <link rel="shortcut icon" href="{{ "favicon.png" | asset_url }}">
                  <meta name="msapplication-TileImage" content="{{ "favicon.png" | asset_url }}">
                  <meta name="viewport" content="width=device-width,initial-scale=1">

                  <title>{{ page_title }}</title>
                  {% comment %}
                    {{ content_for_header }}
                  {% endcomment %}
                </head>
                <body>';
                $footer = '</body></html>';
            }
            $listThemes = $theme->all();
            if ($listThemes) {
                foreach ($listThemes['themes'] as $theme) {
                    $asset = new Asset($clientApi);
                    $parrams = [
                        "asset" => [
                            "key" => "templates/page.bixgrow_embed_portal.liquid",
                            "value" => view('embed_portal', ['iframeUrl' => $iframeUrl, 'header' => $header, 'footer' => $footer, 'css' => $data['css']])->render()
                        ]
                    ];
                    $asset->update($theme['id'], $parrams);
                }
                $page = new Page($clientApi);
                if (!$data['page_id']) {
                    $paramPages = [
                        "page" => [
                            "title" => "Ambassador Portal",
                            "template_suffix" => 'bixgrow_embed_portal',
                            "handle" => $data['path'],
                        ]
                    ];
                    $pageNew = $page->create($paramPages);
                    if (isset($pageNew['errors'])) {
                        return [
                            'status' => false,
                            'message' => isset($pageNew['errors']['handle']) ?
                                'There was a problem while creating a page, the path could be already taken.' : 'Error',
                            'id' => 0
                        ];
                    }
                    if (is_null($groupNotDefault)) {
                        $settingsEmbed = [
                            'is_enable' => $data['isEnable'],
                            'is_fullScreen' => $data['isFullscreen'],
                            'portal_path' => $data['path'],
                            'page_id' =>  $pageNew['page']['id'],
                            'css' => isset($data['css']) ? $data['css'] : '',
                            'group_id' => $data['group_id']
                        ];
                    } else {
                        $settingsEmbed = [
                            'is_enable' => $data['isEnable'],
                            'is_fullScreen' => $data['isFullscreen'],
                            'portal_path' => $data['path'],
                            'page_id' =>  $pageNew['page']['id'],
                            'css' => isset($data['css']) ? $data['css'] : '',
                            'group_id' => $groupNotDefault
                        ];
                    }
                    $shopSetting->embedded_portal_settings = $settingsEmbed;
                    $shopSetting->save();
                    return [
                        'status' => true,
                        'message' => 'Settings updated!',
                        'id' => $pageNew['page']['id']
                    ];
                } else {
                    $paramPages = [
                        "page" => [
                            "id" => $data['page_id'],
                            "handle" => $data['path'],
                        ]
                    ];
                    $pageNew = $page->update($data['page_id'], $paramPages);
                    if (isset($pageNew['errors'])) {
                        return [
                            'status' => false,
                            'message' => isset($pageNew['errors']['handle']) ?
                                'There was a problem while creating a page, the path could be already taken.' : 'Error',
                            'id' => 0
                        ];
                    }
                    if (is_null($groupNotDefault)) {
                        $settingsEmbed = [
                            'is_enable' => $data['isEnable'],
                            'is_fullScreen' => $data['isFullscreen'],
                            'portal_path' => $data['path'],
                            'page_id' =>  $pageNew['page']['id'],
                            'css' => isset($data['css']) ? $data['css'] : '',
                            'group_id' => $data['group_id']
                        ];
                    } else {
                        $settingsEmbed = [
                            'is_enable' => $data['isEnable'],
                            'is_fullScreen' => $data['isFullscreen'],
                            'portal_path' => $data['path'],
                            'page_id' =>  $pageNew['page']['id'],
                            'css' => isset($data['css']) ? $data['css'] : '',
                            'group_id' => $groupNotDefault
                        ];
                    }
                    $shopSetting->embedded_portal_settings = $settingsEmbed;
                    $shopSetting->save();
                    return [
                        'status' => true,
                        'message' => 'Settings updated!',
                        'id' => $pageNew['page']['id']
                    ];
                }
            }
        } catch (Exception $ex) {
            throw new Exception('Error ' . $ex->getMessage());
        }
    }

    public function getPage()
    {
        $shopId = Auth::user()->shop_id;
        $shop = Shop::findOrFail($shopId);
        $shopSetting = $shop->shopSetting()->first();
        $pageSettings = $shopSetting->embedded_portal_settings;
        if ($pageSettings) {
            return $pageSettings;
        } else {
            return null;
        }
        // $shopName = shopNameFromDomain($shop->shop);
        // $clientApi = new ClientApi($shopName, '', $shop->access_token);
        // $page = new Page($clientApi);
        // $pageList=$page->all();
        // dd($pageList);
        // return $pageList;
    }

    public function getCouponAutomatic()
    {
        $shopId = auth()->user()->shop_id;
        $shop = Shop::findOrFail($shopId);
        $shopSetting = $shop->shopSetting()->first();
        $couponAutomatic = $shopSetting->price_rule;
        $CouponCodeTracking = [
            'auto_generate_coupon' => $shopSetting->auto_generate_coupon,
            'allow_changing_discount_code' => $shopSetting->allow_changing_discount_code,
            'allow_specifying_discount_code' => $shopSetting->allow_specifying_discount_code
        ];
        if ($couponAutomatic) {
            return [
                'coupon_code_tracking' => $CouponCodeTracking,
                'coupon_automatic' =>   $couponAutomatic
            ];
        }
        return [
            'coupon_code_tracking' => $CouponCodeTracking,
            'coupon_automatic' =>   null
        ];
    }

    public function settingsAutomaticCoupon($data)
    {
        $shopId = Auth::user()->shop_id;
        if (isset($data['discountAmount']) && $data['discountType'] != 3) {
            $result = Validator::make(
                $data,
                [
                    'discountAmount' => ['required', function ($attribute, $value, $fail) use ($data) {
                        if (intval($value) < 0) {
                            $fail('The discount amount must be greater than or equal to 0');
                        } else {
                            if ($data['discountType'] == 1 && intval($value) > 100) {
                                $fail('The amount must be between 100 and 0');
                            }
                        }
                    }]
                ]
            );
            $result->validate();
        }
        $shop = Shop::findOrFail($shopId);
        $shopName = shopNameFromDomain($shop->shop);
        $shopSetting = $shop->shopSetting()->first();
        $clientApi = new ClientApi($shopName, '', $shop->access_token);
        $discountCode = new DiscountCode($clientApi);
        if ($data['isExist']) {
            $getDiscountCode = $discountCode->search(['code' => $data['couponCode']]);
            if (isset($getDiscountCode['discount_code']['price_rule_id'])) {
                $priceRule = [
                    'price_rule' => $getDiscountCode['discount_code']['price_rule_id'],
                    'code' => $getDiscountCode['discount_code']['code'],
                    'coupon_style' => $data['coupon_style']
                ];
                $shopSetting->price_rule = $priceRule;
                $shopSetting->save();
                return [
                    'status' => true,
                    'message' => 'Automatic coupon setup success'
                ];
            }
            if (isset($getDiscountCode['errors'])) {
                return [
                    'status' => false,
                    'message' => 'This coupon does not exist in your Shopify store'
                ];
            }
        } else {
            // $startAt = Carbon::createFromTimestamp(intval($data['startAt']))->toDayDateTimeString();
            $params = ["price_rule" => [
                "target_type" => $data['discountType'] == 3 ? "shipping_line" : "line_item",
                "title" => $data['couponCode'],
                "target_selection" => "all",
                "allocation_method" => $data['discountType'] == 3 ? "each" : "across",
                "value_type" => $this->convertDiscountType($data['discountType']),
                "value" => $this->convertDiscountAmount($data['discountType'], $data['discountAmount']),
                'starts_at' => Carbon::now()->format('Y-m-d\TH:i:s'),
                "customer_selection" => "all"
            ]];

            $priceRuleAPI = new PriceRule($clientApi);
            $priceRuleAPI = $priceRuleAPI->create($params);
            $paramsDiscountCode = ["discount_code" => [
                "code" => $data['couponCode']
            ]];
            if (isset($priceRuleAPI['errors'])) {
                return [
                    'status' => false,
                    'message' => 'Error'
                ];
            }
            $couponAPI = $discountCode->create($priceRuleAPI['price_rule']['id'], $paramsDiscountCode);
            if (isset($couponAPI['errors'])) {
                return [
                    'status' => false,
                    'message' => 'This coupon has already existed on your Shopify discount,
                 please select "Use an existing coupon"'
                ];
            }
            $priceRule = [
                'price_rule' => $couponAPI['discount_code']['price_rule_id'],
                'code' => $couponAPI['discount_code']['code'],
                'coupon_style' => $data['coupon_style']
            ];
            $shopSetting->price_rule = $priceRule;
            $shopSetting->save();
            return [
                'status' => true,
                'message' => 'Automatic coupon setup success'
            ];
        }
    }
    protected function convertDiscountType($type)
    {
        $valueType = '';
        if ($type == 1) {
            $valueType = 'percentage';
        } else {
            if ($type == 2) {
                $valueType = 'fixed_amount';
            } else {
                $valueType = 'percentage';
            }
        }
        return $valueType;
    }

    protected function convertDiscountAmount($type, $value)
    {
        if ($type == 1) {
            $value = $value * -1.00;
        }
        if ($type == 2) {
            $value = $value * -1.00;
        }
        if ($type == 3) {
            $value = -100.0;
        }
        return $value;
    }
    public function updateCouponTracking($data)
    {
        $shopId = Auth::user()->shop_id;
        $shopSetting = ShopSetting::where('shop_id', $shopId)->first();
        if (isset($data['type']) && $data['type'] == 'auto_generate_coupon') {
            $shopSetting->auto_generate_coupon = $data['status'];
            $shopSetting->save();
        }
        return $shopSetting;
    }
    public function updateYourNotifications($data)
    {
        $shopId = auth()->user()->shop_id;
        $shopSetting = ShopSetting::where('shop_id', $shopId)->first();
        if ($data['type'] == 'new_order') {
            $shopSetting->notify_self_new_order = $data['status'];
            $shopSetting->save();
            return true;
        }
        if ($data['type'] == 'new_registrations') {
            $shopSetting->notify_self_new_registrations = $data['status'];
            $shopSetting->save();
            return true;
        }
    }
    public function uploadLogoShop($data)
    {
        $shopId = auth()->user()->shop_id;
        $shopSetting = ShopSetting::where('shop_id', $shopId)->first();
        if (isset($data['logo'])) {
            $data['logo'] = $data['path_name'];;
            $shopSetting->logo = $data['logo'];
            $shopSetting->path_name = $data['path_name'];
            $shopSetting->save();
            $shopSetting->refresh();
            return [
                'logo' =>  $shopSetting->logo,
                'path_name' => $shopSetting->path_name
            ];
        } else {
            return null;
        }
    }

    public function doneStarted()
    {
        $shopId = auth()->user()->shop_id;
        ShopSetting::where('shop_id', $shopId)->update(['is_done_started' => 1, 'is_skip_started' => 1]);
    }

    public function skipStarted()
    {
        $shopId = auth()->user()->shop_id;
        ShopSetting::where('shop_id', $shopId)->update(['is_skip_started' => 1]);
    }
    public function updateDefaultLink($data)
    {
        $result = Validator::make(
            $data,
            [
                'default_affiliate_link' => [
                    'required'
                ]
            ]
        );
        $result->validate();
        $shopId = auth()->user()->shop_id;
        $shopSetting = ShopSetting::where('shop_id', $shopId)->first();
        $shopSetting->default_affiliate_link = $data['default_affiliate_link'];
        $shopSetting->allow_affiliates_custom_link = $data['allow_affiliates_custom_link'];
        $shopSetting->save();
        return $shopSetting;
    }
    public function uploadImagePostCheckout($data, $file)
    {
        $fileNameHash =  generateRandomString() . '_' . time();
        $filePath = $file->storePubliclyAs('checkout', $fileNameHash, 's3');
        $filePathDB = Storage::disk('s3')->url($filePath);
        return [
            'src' => $filePath,
            'pathCDN' => $filePathDB
        ];
    }
    public function updateCheckoutPopupData($data)
    {
        $shopId = auth()->user()->shop_id;
        $shopSettings = ShopSetting::where('shop_id', $shopId)->first();
        $shopSettings->checkout_popup_data = $data['checkout_popup_data'];
        $shopSettings->save();
        return true;
    }
    public function activePopupCheckout($data)
    {
        $shopId = auth()->user()->shop_id;
        $shop = Shop::findOrFail($shopId);
        $shopName = shopNameFromDomain($shop->shop);
        $clientApi = new ClientApi($shopName, '', $shop->access_token);
        $scriptTag = new ScriptTag($clientApi);
        $shopSettings = ShopSetting::where('shop_id', $shopId)->first();
        if (!intval($data['activate_popup_checkout'])) {
            $scriptTagId = intval($shopSettings->script_tag_id);
            if ($scriptTagId) {
                $getAllScriptTag = $scriptTag->all();
                foreach ($getAllScriptTag['script_tags'] as $script) {

                    if ($script['display_scope'] == "order_status" && str_contains($script['src'], 'bixgrow-popup-checkout.js')) {
                        $scriptTag->delete($script['id']);
                    }
                }
            }
            $shopSettings->activate_popup_checkout = 0;
            $shopSettings->script_tag_id = null;
            $shopSettings->save();
            return true;
        } else {
            $arr = [
                "script_tag" => [
                    "event" => "onload",
                    "src" => 'https://app.' . config('endpoint.main_domain') . '/settings/bixgrow-popup-checkout.js',
                    "display_scope" => "order_status"
                ]
            ];
            $getAllScriptTag = $scriptTag->all();
            foreach ($getAllScriptTag['script_tags'] as $script) {

                if ($script['display_scope'] == "order_status" && str_contains($script['src'], 'bixgrow-popup-checkout.js')) {
                    $scriptTag->delete($script['id']);
                }
            }
            $scriptTag = $scriptTag->create($arr);
            if (isset($scriptTag['errors'])) {
                return false;
            }
            $shopSettings->script_tag_id = $scriptTag['script_tag']['id'];
            $shopSettings->activate_popup_checkout = 1;
            $shopSettings->save();
            return true;
        }
    }

    public function updateTermAndPolicy($data)
    {
        $shopSetting = $this->getShopSetting();
        $shopSetting->term = $data['term'];
        $shopSetting->policy = $data['policy'];
        $shopSetting->is_enable_term_policy = $data['status'];
        $shopSetting->save();
        return $shopSetting;
    }

    public function getTermAndPolicy()
    {
        $shopId = auth()->user()->shop_id;
        $shopSetting = ShopSetting::where('shop_id', $shopId)->select('term', 'policy', 'is_enable_term_policy')->first();
        return $shopSetting;
    }

    public function getSampleTermPolicy()
    {
        return [
            'term' => config('myconfig.termspolicy.terms'),
            'policy' => config('myconfig.termspolicy.policy')
        ];
    }
    public function getAffiliateLanguage()
    {
        $shopId = auth()->user()->shop_id;
        $shopSetting = ShopSetting::where('shop_id', $shopId)->select('default_affiliate_language', 'enable_affiliate_language', 'auto_detect_language')->first();
        return $shopSetting;
    }

    public function updateAffiliateLanguage($data)
    {
        $shopId = auth()->user()->shop_id;
        $shopSetting = ShopSetting::where('shop_id', $shopId)->first();
        $shopSetting->default_affiliate_language = $data['default_affiliate_language'];
        $shopSetting->enable_affiliate_language = $data['enable_affiliate_language'];
        $shopSetting->auto_detect_language = $data['auto_detect_language'];
        $shopSetting->save();
        return true;
    }

    public function getTranslations($data, $locale)
    {
        $shopId = auth()->user()->shop_id;
        $condittionsDefault = [
            ['t3.locale', 'en'],
            ['t3.shop', 'default']
        ];
        // // $query = '';
        if (isset($data['search']) && !empty($data['search'])) {
            $query = addslashes($data["search"]);
            $condittionsDefault[] = ['t3.text', 'like', '%' . $query . '%'];
        }
        // $defaultEnglishTranslations = DB::table('translates')
        // ->where('shop', 'default')->where($condittionsDefault)
        // ->select('key', 'text')->get();
        // $defaultEnglishTranslations = $this->convertArrayIndexToKey($defaultEnglishTranslations);
        $condittions = [['t1.locale', $locale]];
        $condittions[] = ['t1.shop', 'default'];
        // if (isset($data['search']) && !empty($data['search'])) {
        //     $condittions[] = ['t1.text', 'like', '%' . $query . '%'];
        // }
        $translations = DB::table('translates as t1')->where($condittions)
            ->leftJoin('translates as t2', function ($leftJoin) use ($shopId) {
                $leftJoin->on('t1.locale', '=', 't2.locale');
                $leftJoin->on('t1.key', '=', 't2.key');
                $leftJoin->where('t2.shop', $shopId);
            })->join('translates as t3', 't1.key', '=', 't3.key')->where($condittionsDefault)->select('t1.*', 't2.text as t_text', 't3.text as textEn')->paginate($data['paginate']);
        return
            [
                // 'defaultEnglishTranslations' => $defaultEnglishTranslations,
                'translations' => $translations
            ];
    }

    protected function convertArrayIndexToKey($arr)
    {
        $arrTemp = [];
        foreach ($arr as $v) {
            $arrTemp[$v->key] = $v->text;
        }
        return $arrTemp;
    }

    public function updateTranslations($data, $locale)
    {
        $shopId = auth()->user()->shop_id;
        foreach ($data as $key => $text) {
            Translate::updateOrCreate(
                [
                    'shop' => $shopId,
                    'locale' => $locale,
                    'key' => $key,
                ],
                [
                    'shop' => $shopId,
                    'locale' => $locale,
                    'key' => $key,
                    'text' => $text
                ]
            );
        }
        return true;
    }

    public function settingSelfGeneratingCoupon($data)
    {
        $shopSetting = $this->getShopSetting();
        $shopSetting->allow_self_generating_coupon = $data['allow_self_generating_coupon'];
        $shopSetting->autopay_discount_code = $data['autopay_discount_code'];
        $shopSetting->save();
    }

    public function deleteAutomaticCoupon()
    {
        $shopSettings = $this->getShopSetting();
        $shopSettings->auto_generate_coupon = 0;
        $shopSettings->price_rule = null;
        $shopSettings->save();
        return true;
    }

    public function getSystemNotifications()
    {
        $user = auth()->user();
        $user->unreadNotifications()->update(['read_at' => now()]);
        $paginator = $user->notifications()->select('data', 'created_at')->paginate(10);
        $paginator->getCollection()->transform(function ($value) {
            $value['time_stamp'] = isset($value['created_at'])? Carbon::parse($value['created_at'])->getTimestamp():'';
            return $value;
        });
        return $paginator;
    }

    public function loadMoreSystemNotifications()
    {
        $user = auth()->user();
        $paginator = $user->notifications()->select('data', 'created_at')->paginate(10);
        $paginator->getCollection()->transform(function ($value) {
            $value['time_stamp'] = isset($value['created_at'])? Carbon::parse($value['created_at'])->getTimestamp():'';
            return $value;
        });
        return $paginator;
    }

    public function updateLoginFormConfig($data)
    {
        $shopSetting = $this->getShopSetting();
        info($data['data']);
        $shopSetting->login_page_config = $data['data'];
        $shopSetting->save();
        return $shopSetting->login_page_config;
    }

    public function getLoginFormConfig(){
        $shopSetting = ShopSetting::where('shop_id', auth()->user()->shop_id)->select('shop_id', 'login_page_config')->first();
        return $shopSetting;
    }
}
