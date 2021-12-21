<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\ScriptTag;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Events\Webhook;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use App\Models\ShopSetting;
use App\Models\Group;


class AppInstalled
{

    private $scriptTag;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {

        $shop = $event->shop();
        $shopName = shopNameFromDomain($shop->shop);
        $this->createShopSetting($shop, $shopName);
        $clientApi = new ClientApi($shopName, '', $shop->access_token);
        $this->addScripttag($clientApi);
        $this->createDefaultGroup($shop->id);
        $this->addWebhook($clientApi, $shop->id);

    }

    private function addScripttag($clientApi)
    {
        $scriptTag = new ScriptTag($clientApi);
        
        if (config('app.env') == 'local') {
            $url = config('app.url').'/scripttag/test.js';
        } else {
            $url = config('app.url').'/scripttag/bixgrow-track.js';
        }
        
        $scriptTag->create(['script_tag' => [
            'event' => 'onload',
            'src'   => $url,
        ]]);

    }

    private function addWebhook($clientApi,$shopId)
    {

        if (config('app.env') == 'local') {
            $paidAddress = 'https://0fa7-171-241-26-145.ngrok.io/webhook/order_paid?shop_id='.$shopId;
        } else {
            $paidAddress = route('webhook.order_paid',['shop_id'=>$shopId]);
        }
        $webhook = new Webhook($clientApi);
        $webhook->create(['webhook' => [
            'topic' => 'orders/paid',
            'address'   => $paidAddress,
            'format'=> 'json'
        ]]);

    }

    private function createDefaultGroup($shopId)
    {
        $checkExistDefault = Group::where('shop_id', $shopId)->where('is_default', 1)->first();
        if(!$checkExistDefault){
            $defaultGroup = new Group;
            $defaultGroup->name = "Default";
            $defaultGroup->commission_type = 1;
            $defaultGroup->commission_amount = 10;
            $defaultGroup->is_default = 1;
            $defaultGroup->is_active = 1;
            $defaultGroup->shop_id = $shopId;
            $defaultGroup->save();
        }
        
    }

    private function createShopSetting($shop, $shopName)
    {
        $shopInfo = $shop->info()->select('shop_owner','email','domain','iana_timezone')->first();
        $check = ShopSetting::where('shop_id', $shop->id)->first();
        if(!$check) {
            $setting = new ShopSetting;
            $setting->shop_id = $shop->id;
            $setting->sub_domain = $shopName;
            $setting->brand_name = $shopName;
            $setting->contact_name = $shopInfo->shop_owner;
            $setting->contact_email = $shopInfo->email;
            $setting->timezone = $shopInfo->iana_timezone;
            $setting->default_affiliate_link ='https://'.$shopInfo->domain;
            $setting->save();
        }

    }
}
