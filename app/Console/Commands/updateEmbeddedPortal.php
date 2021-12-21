<?php

namespace App\Console\Commands;

use App\Models\Shop;
use App\Models\Group;
use App\Models\ShopSetting;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\Asset;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\Page;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\Theme;
use Exception;
use Illuminate\Console\Command;

class updateEmbeddedPortal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:updateEmbeddedPortal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update Embedded Portal to BixGrow';

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
        $shopSettings=ShopSetting::get();
        foreach($shopSettings as $shopSetting)
        {
            if($shopSetting->embedded_portal_settings)
            {
                $data=[];
                $data['isEnable']=$shopSetting->embedded_portal_settings['is_enable'];
                $data['page_id']=$shopSetting->embedded_portal_settings['page_id'];
                $data['isFullscreen']=$shopSetting->embedded_portal_settings['is_fullScreen'];
                $data['path']=$shopSetting->embedded_portal_settings['portal_path'];
                $data['css']=$shopSetting->embedded_portal_settings['css'];
                if(!isset($shopSetting->embedded_portal_settings['group_id'])) {
                    $groupDefault = Group::where('shop_id', $shopSetting->shop_id)->where('is_default', 1)->select('id')->first();
                    $data['group_id'] = $groupDefault->id;
                }else{
                    $data['group_id'] = $shopSetting->embedded_portal_settings['group_id'];
                }
                $shop = Shop::findOrFail($shopSetting->shop_id);
                $shopName = shopNameFromDomain($shop->shop);
                $shopSetting = $shop->shopSetting()->first();
                $subDomain = $shopSetting->sub_domain;
                if(isset($groupDefault)) {
                    $iframeUrl = 'https://' . $subDomain . '.' . config('endpoint.main_domain') . '/#/register';
                }else{
                    $group = Group::find($data['group_id']);
                    $iframeUrl = 'https://' . $subDomain . '.' . config('endpoint.main_domain') . '/#/register/'.$group->slug;
                }
                $clientApi = new ClientApi($shopName, '', $shop->access_token);
                if(!$data['isEnable'])
                {
                    if($data['page_id'])
                    {
                        $page = new Page($clientApi);
                       $result= $page->delete($data['page_id']);
                    }
                    $settingsEmbed = [
                        'is_enable' => $data['isEnable'],
                        'is_fullScreen' => $data['isFullscreen'],
                        'portal_path' => $data['path'],
                        'page_id' => 0,
                        'css' => isset($data['css'])? $data['css']:'',
                        'group_id' => $data['group_id']
                    ];
                    $shopSetting->embedded_portal_settings = $settingsEmbed;
                    $shopSetting->save();
                    return[
                        'status'=>false,
                        'message'=>'Portal page disable',
                        'id'=>0
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
                                    "value" => view('embed_portal', ['iframeUrl' => $iframeUrl, 'header' => $header, 'footer' => $footer,'css' => $data['css']])->render()
                                ]
                            ];
                            $asset->update($theme['id'], $parrams);
                        }
                        $page = new Page($clientApi);
                        if (!$data['page_id']) {
                            $paramPages = [
                                "page" => [
                                    "title" => "Embedded portall",
                                    "template_suffix" => 'bixgrow_embed_portal',
                                    "handle" => $data['path'],
                                ]
                            ];
                            $pageNew = $page->create($paramPages);
                            if(isset($pageNew['errors']))
                            {
                                return[
                                    'status'=>false,
                                    'message'=>isset($pageNew['errors']['handle'])?
                                    'There was a problem while creating a page, the path could be already taken.':'Error',
                                    'id'=>0
                                ];
                            }
                            $settingsEmbed = [
                                'is_enable' => $data['isEnable'],
                                'is_fullScreen' => $data['isFullscreen'],
                                'portal_path' => $data['path'],
                                'page_id' =>  $pageNew['page']['id'],
                                'css' => isset($data['css'])? $data['css']:'',
                                'group_id' => $data['group_id']
                            ];
                            $shopSetting->embedded_portal_settings = $settingsEmbed;
                            $shopSetting->save();
                            return[
                                'status'=>true,
                                'message'=>'Settings update success',
                                'id'=>$pageNew['page']['id']
                            ];
                        }
                        else{
                            $paramPages = [
                                "page" => [
                                    "id" => $data['page_id'],
                                    "handle" => $data['path'],
                                ]
                            ];
                            $pageNew = $page->update( $data['page_id'],$paramPages);
                            if(isset($pageNew['errors']))
                            {
                                return[
                                    'status'=>false,
                                    'message'=>isset($pageNew['errors']['handle'])?
                                    'There was a problem while creating a page, the path could be already taken.':'Error',
                                    'id'=>0
                                ];
                            }
                            $settingsEmbed=[
                                'is_enable' => $data['isEnable'],
                                'is_fullScreen' => $data['isFullscreen'],
                                'portal_path' =>$pageNew['page']['handle'],
                                "page_id" =>  $pageNew['page']['id'],
                                'css' => isset($data['css'])? $data['css']:'',
                                'group_id' => $data['group_id']
                            ];
                            $shopSetting->embedded_portal_settings = $settingsEmbed;
                            $shopSetting->save();
                            return[
                                'status'=>true,
                                'message'=>'Settings update success',
                                'id'=>$pageNew['page']['id']
                            ];
                        }
                    }
                } catch (Exception $ex) {
                    throw new Exception('Error ' . $ex->getMessage());
                }
            }
        }
    }
}
