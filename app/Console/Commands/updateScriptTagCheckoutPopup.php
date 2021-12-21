<?php

namespace App\Console\Commands;

use App\Models\Shop;
use App\Models\ShopSetting;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\ScriptTag;
use Illuminate\Console\Command;

class updateScriptTagCheckoutPopup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:updateScriptTagCheckoutPopup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update scripttag checkout popup';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $shops=Shop::whereNotNull('access_token')->get();
        foreach($shops as $shop)
        {
            $shopName = shopNameFromDomain($shop->shop);
            $shopSetting = ShopSetting::where('shop_id',$shop->id)->first();
            $clientApi = new ClientApi($shopName, '', $shop->access_token);
            $scriptTag = new ScriptTag($clientApi);
            $arr=[
                "script_tag"=> [
                    "event"=> "onload",
                    "src"=>'https://app.'.config('endpoint.main_domain').'/settings/bixgrow-popup-checkout.js',
                    "display_scope"=>"order_status"
                ]
                ];
            if($shopSetting->activate_popup_checkout)
            {
                $getAllScriptTag= $scriptTag->all();
                foreach($getAllScriptTag['script_tags'] as $script)
                {

                    if($script['display_scope'] == "order_status" && str_contains($script['src'], 'popup-checkout'))
                    {
                        $scriptTag->delete($script['id']);
                    }
                }
                $scriptTag->create($arr);
                $shopSetting->script_tag_id = $scriptTag['script_tag']['id'];
                $shopSetting->save();
            }

        }
    }
}
