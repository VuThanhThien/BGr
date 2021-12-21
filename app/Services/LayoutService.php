<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Group;
use App\Models\Shop;
use DB;
use App\Models\PaymentMethod;
use App\Models\ShopSetting;
use Illuminate\Support\Facades\Auth;
use App\Services\GroupService;
use Illuminate\Notifications\Notification;

Class LayoutService
{

    private GroupService $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function getData()
    {
        
        $shopId = Auth::user()->shop_id;
        $shopSetting = ShopSetting::where('shop_id', $shopId)->with('info:shop_id,currency,money_format,name,domain,myshopify_domain')->first();
        if(!isset($shopSetting->from_contact_email) ||$shopSetting->from_contact_email && !$shopSetting->is_verified_from_email)
        {
            $shopSetting->from_contact_email=config('mail.from.address');                                                               
        }
        $paymentMethodAvailable = PaymentMethod::where('payment_methods.shop_id', $shopId)->orWhere('payment_methods.shop_id', 0)
        ->leftJoin('shop_payment_methods', function ($join) use ($shopId) {
            $join->on('payment_methods.id', '=', 'shop_payment_methods.method_id');
            $join->where('shop_payment_methods.shop_id', '=', $shopId);
        })
        // ->leftJoin('shop_payment_methods', 'payment_methods.id', '=', 'shop_payment_methods.method_id')
        // ->where('shop_payment_methods.shop_id', $shopId)
        ->select('payment_methods.id', 'payment_methods.key', 'payment_methods.name', 'payment_methods.fields', 'payment_methods.type', 'shop_payment_methods.shop_id', DB::raw('(CASE WHEN shop_payment_methods.shop_id IS NULL THEN 0 ELSE 1 END) as status'))
        ->get();
        $groups = $this->groupService->getList(null);
        $feedback = \App\Models\FeedBack::where('shop_id', $shopId)->first();
        $isFeedBack = $feedback ? true : false;
        $unreadNotifications = Auth::user()->unreadNotifications()->count();
        return [
            'shopSettings' => $shopSetting,
            'paymentMethodAvailable' => $paymentMethodAvailable,
            'groups' => $groups,
            'is_feedback' => $isFeedBack,
            'coutUnreadNotifications' =>  $unreadNotifications
        ];
    }
}
