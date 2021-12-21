<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateCoupon;
use App\Models\AffiliateTier;
use App\Models\Group;
use App\Models\Shop;
use Carbon\Carbon;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Discounts\DiscountCode;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\Asset;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\Theme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupService
{
    public function getList($requestData)
    {
        $groups = Auth::user()->groups()->select(
            'allow_changing_discount_code',
            'allow_specifying_discount_code',
            'auto_approve_affiliate',
            'auto_approve_order',
            'auto_generate_coupon',
            'commission_amount',
            'commission_type',
            'cookie_days',
            'created_at',
            'customer_commission_amount',
            'customer_commission_type',
            'description',
            'exclude_shipping',
            'exclude_tax',
            'exclude_vat',
            'holding_period',
            'id',
            'include_discounts',
            'include_eu_vat',
            'is_active',
            'is_default',
            'is_enable_mlm',
            'is_private',
            'minimum_payable_amount',
            'mlm_bonus',
            'mlm_commission_on',
            'mlm_tree',
            'multiple_orders_a_customer_type',
            'name',
            'shop_id',
            'updated_at',
            'slug'
        )
            ->withCount('affiliates')
            ->orderBy('created_at', 'desc')
            ->get();
        return $groups;
    }

    public function findById($id)
    {
        $group = Auth::user()->groups()->where('id', $id)->first();
        if (!auth()->user()->can('view', $group)) {
            throw new \Illuminate\Auth\Access\AuthorizationException();
        }
        return $group;
    }

    public function store($data)
    {
        $shopId = Auth::user()->shop_id;
        $group = new Group;
        $group->name = $data['name'];
        $group->shop_id = $shopId;
        $group->commission_type = $data['commission_type'];
        $group->commission_amount = $data['commission_amount'];
        $group->save();
        $group->refresh();
        return $group;
    }

    public function update($data, $id)
    {
        $shopId = Auth::user()->shop_id;
        $result=Validator::make($data,
        [
            'defined_coupon_customer' =>[
                function ($attribute, $value, $fail) use ($shopId,$data) {
                    if($data['customer_commission_type'] == 2)
                    {
                        $checkAffiliateExist = AffiliateCoupon::where([
                            'shop_id' => $shopId,
                            'code' => $value
                        ])->get();
                        if ($checkAffiliateExist->count() != 0) {
                            $fail('This coupon has been assigned to an affiliate');
                        }
                    }
                }
            ]
        ]);  
        $result->validate();
        $group = Group::find($id);
        if (!auth()->user()->can('update', $group)) {
            throw new \Illuminate\Auth\Access\AuthorizationException();
        }
        $shop = Shop::findOrFail($shopId);
        $shopName = shopNameFromDomain($shop->shop);
        $clientApi = new ClientApi($shopName, '', $shop->access_token);
        if ($data['customer_commission_type'] == 2) {
            $couponAPI = new DiscountCode($clientApi);
            $couponAPISearch = $couponAPI->search(['code' => $data['defined_coupon_customer']]);
            if (isset($couponAPISearch['errors'])) {
                return false;
            }
        }
        $customer_commission_type_old = $group->customer_commission_type;
        $group->name = $data['name'];
        $group->shop_id = $shopId;
        $group->commission_type = $data['commission_type'];
        $group->commission_amount = $data['commission_amount'];
        $group->is_active = $data['is_active'];
        $group->is_private = $data['is_private'];
        $group->customer_commission_type = $data['customer_commission_type'];
        $group->customer_commission_amount = $data['customer_commission_amount'];
        $group->cookie_days = $data['cookie_days'];
        $group->auto_approve_order = $data['auto_approve_order'];
        $group->auto_approve_affiliate = $data['auto_approve_affiliate'];
        $group->exclude_shipping = $data['exclude_shipping'];
        $group->exclude_tax = $data['exclude_tax'];
        $group->include_discounts = $data['include_discounts'];
        $group->allow_changing_discount_code = $data['allow_changing_discount_code'];
        $group->allow_specifying_discount_code = $data['allow_specifying_discount_code'];
        $group->auto_generate_coupon = $data['auto_generate_coupon'];
        $group->defined_coupon_customer = $data['defined_coupon_customer'];
        $group->payment_methods = $data['payment_methods'];
        $group->save();
        if ($group->customer_commission_type != $customer_commission_type_old) {
            $theme = new Theme($clientApi);
            $listThemes = $theme->all();
            $groups = Group::where('customer_commission_type', '!=', 0)->where('shop_id', $shopId)->pluck('id')->toArray();
            foreach ($listThemes['themes'] as $theme) {
                $asset = new Asset($clientApi);
                $parrams = [
                    "asset" => [
                        "key" => "assets/bixgrow_config.js",
                        "value" => view('bixgrow_config', ['groups' => $groups])->render()
                    ]
                ];
                $asset->update($theme['id'], $parrams);
                if ($theme['role'] == 'main' || $theme['role'] == 'mobile') {
                    // Get layout from theme and replace script to it
                    $this->addIncludeAssetsToTheme($theme['id'], $asset);
                }
            }
        }
        return $group;
    }

    public function delete($id)
    {
        $group = Group::find($id);
        if (!auth()->user()->can('delete', $group)) {
            throw new \Illuminate\Auth\Access\AuthorizationException();
        }
        $affiliateTier = AffiliateTier::where('shop_id',$group->shop_id)->first();
        if($affiliateTier)
        {
           foreach($affiliateTier->data_levels as $data)
           {
                if($data['program_id'] ==  $id)
                {
                    return false;
                }
           } 
        }
        $group->delete();
        return true;
    }

    public function updateMLM($data, $id)
    {
        $group = Group::findOrFail($id);
        if (!auth()->user()->can('updateMLM', $group)) {
            throw new \Illuminate\Auth\Access\AuthorizationException();
        }
        $group->is_enable_mlm = $data['is_enable'];
        $group->mlm_commission_on = $data['mlm_commission_on'];
        $group->mlm_bonus = $data['mlm_bonus'] ? $data['mlm_bonus'] : 0.00;
        $group->mlm_tree = $data['mlm_tree'];
        $group->save();
        return $group;
    }

    public function setDefault($id)
    {

        Group::where('shop_id', Auth::user()->shop_id)->update(['is_default' => 0]);
        $group = Group::findOrFail($id);
        if (!auth()->user()->can('setDefault', $group)) {
            throw new \Illuminate\Auth\Access\AuthorizationException();
        }
        $group->is_default = 1;
        $group->save();
        return true;
    }
    public function countAffilate($id)
    {
        return Affiliate::where('group_id', '=', $id)->count();
    }

    public function changeActive($id)
    {
        $group = Group::find($id);
        if (!auth()->user()->can('changeActive', $group)) {
            throw new \Illuminate\Auth\Access\AuthorizationException();
        }
        $group->is_active = !$group->is_active;
        $group->save();
        return true;
    }

    public function updateRegistrationForm($data, $id)
    {
        $group = Group::find($id);
        $group->registration_form = $data['registration_form'];
        $group->save();
        return true;
    }

    public function uploadAsideImage($data, $file)
    {
        $fileNameOrigin = $data->getClientOriginalName();
        $fileNameHash = time() . '_' . generateRandomString(5);
        $filePath = $file->storePubliclyAs('asides', $fileNameHash, 's3');
        $filePathDB = \Storage::disk('s3')->url($filePath);
        return [
            'path' => $filePathDB,
        ];
    }

    public function defaultGroup()
    {
        $shopId = auth()->user()->shop_id;
        $group = Group::where('shop_id', $shopId)->where('is_default', 1)->first();
        return $group;
    }

    private function copyProductCommission($fromGroup, $toGroup)
    {
        $data = [];

        \App\Models\ProductCommission::where('group_id', $fromGroup)
            ->chunk(200, function ($products) use ($toGroup, &$data) {
                $rows = [];
                foreach ($products as $p) {
                    $rows[] = [
                        'variant_id' => $p->variant_id,
                        'product_id' => $p->product_id,
                        'product_title' => $p->product_title,
                        'variant_title' => $p->variant_title,
                        'image_url' => $p->image_url,
                        'commission_type' => $p->commission_type,
                        'commission_amount' => $p->commission_amount,
                        'group_id' => $toGroup,
                        'shop_id' => $p->shop_id,
                        'collection_id' => NULL

                    ];
                }
                $data[] = $rows;
            });
        info($data);
        foreach ($data as $d) {
            \App\Models\ProductCommission::insert($d);
        }
    }

    public function duplicate($requestData)
    {
        $originGroup = Group::find($requestData['origin_group']);
        $newGroup = $originGroup->replicate();
        $newGroup->is_default = 0;
        $newGroup->name = $requestData['name'];
        if (!in_array('multilevel_marketing', $requestData['options'])) {
            $newGroup->is_enable_mlm = 0;
            $newGroup->mlm_tree = NULL;
            $newGroup->mlm_commission_on = 1;
            $newGroup->mlm_bonus = 0.00;
        }
        if (!in_array('affiliate_registration', $requestData['options'])) {
            $newGroup->registration_form = NULL;
        }
        $newGroup->slug = NULL;
        $newGroup->save();
        if (in_array('product_commission', $requestData['options'])) {
            $this->copyProductCommission($requestData['origin_group'], $newGroup->id);
        }
        return $newGroup;
    }

    protected function addIncludeAssetsToTheme($themeId, $asset)
    {
        $params = [
            "asset" => [
                "key" => "layout/theme.liquid"
            ]
        ];
        $scriptTagFileName = 'bixgrow_config.js';
        $resultLayout = $asset->all($themeId, $params)['asset'];
        if (strpos($resultLayout['value'], $scriptTagFileName) == false) {
            $fileLayoutContent = str_replace(
                '</head>',
                '{{ \'' . $scriptTagFileName . '\' | asset_url | script_tag }}'
                    . PHP_EOL . '</head>',
                $resultLayout['value']
            );
            $updateTheme = [
                "asset" => [
                    "key" => "layout/theme.liquid",
                    "value" => $fileLayoutContent
                ]
            ];
            $asset->update($themeId, $updateTheme);
        }
    }

    public function getAutomaticCouponCustomer($data)
    {
        $shopId = Shop::where('shop', $data['shop'])->first()->id;
        if (empty($data['affiliateId'])) {
            return null;
        }
        $group = Group::where('shop_id', $shopId)->where('is_active',1)->whereHas('affiliates', function ($query) use ($data) {
            $query->where('hash_code', $data['affiliateId']);
        })->select('id', 'customer_commission_type', 'defined_coupon_customer')->first();
        if ($group) {
            if ($group->customer_commission_type) {
                $couponCode = $this->getCouponCustomerByGroup($group->customer_commission_type, $group->defined_coupon_customer, $shopId, $data['affiliateId']);
                return  $couponCode;
            } else {
                return null;
            }
        }
        return null;
    }

    protected function getCouponCustomerByGroup($customer_commission_type, $defined_coupon_customer, $shopId, $affiliateId)
    {
        if ($customer_commission_type == 2) {

            return [
                'couponCode' => $defined_coupon_customer
            ];
        } else {
            $affiliateCoupon = AffiliateCoupon::where('shop_id', $shopId);
            $affiliate = Affiliate::where('hash_code', $affiliateId)->first();
            if ($affiliate) {
                $affiliateCoupon = $affiliateCoupon->where('affiliate_id', $affiliate->id)->orderBy('created_at', 'ASC')->select('id', 'code')->first();
                if ($affiliateCoupon) {
                    return [
                        'couponCode' => $affiliateCoupon->code
                    ];
                } else {
                    return null;
                }
            } else {
                return null;
            }
        }
    }
}
