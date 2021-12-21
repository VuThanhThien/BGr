<?php

namespace App\Http\Controllers;

use App\Jobs\ImportAffiliateTranslation;
use App\Models\Shop;
use App\Models\Group;
use App\Models\ShopInfo;
use App\Models\ShopSetting;
use App\Models\Translate;
use App\Models\AffiliateTier;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\Asset;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\Page;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\Theme;
use Exception;
use Illuminate\Console\Command;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\ScriptTag;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Services\CategoryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Jobs\HandleAffiliateTierMonthly;

class DashboardController extends Controller
{
    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        $notification = \DB::table('notifications')->delete();
        dd($notification);
        // $shops = AffiliateTier::where(function($query){
        //     $query->where('affiliate_tier.level_condition', AffiliateTier::MONTHLY_SALES_AUTO_DOWNGRADE);
        //     $query->orWhere('affiliate_tier.level_condition', AffiliateTier::MONTHLY_ORDERS_AUTO_DOWNGRADE);
        // })
        // ->join('shop_settings', 'shop_settings.shop_id', '=', 'affiliate_tier.shop_id')
        // ->whereIn('shop_settings.timezone', ['Asia/Phnom_Penh'])
        // ->where('affiliate_tier.status', 1)
        // ->select('affiliate_tier.shop_id as id', 'affiliate_tier.data_levels')
        // ->get();
        // dd($shops);
       
        // $shops=Shop::whereNotNull('access_token')->get();
        // foreach($shops as $shop)
        // {
        //     $shopName = shopNameFromDomain($shop->shop);
        //     $shopSetting = ShopSetting::where('shop_id',$shop->id)->first();
        //     $clientApi = new ClientApi($shopName, '', $shop->access_token);
        //     $scriptTag = new ScriptTag($clientApi);
            
        //     if($shopSetting->activate_popup_checkout)
        //     {
                
        //         $getAllScriptTag= $scriptTag->all();
        //         info($getAllScriptTag);
                
                
        //     }

        // }
        // dd(json_decode(config('myconfig.constants.all_timezone')));
        // $listTimezone = \DateTimeZone::listIdentifiers();
        // // dd($listTimezone);
        // $data = [];
        // foreach($listTimezone as $l) {
            
            // $from = Carbon::now()->tz('Asia/Phnom_Penh')->setTimezone('UTC');
            // dd($from);
        //     $data[] = $from->format('Y-m-d H:i:s');
        //     echo($l.' : '.$from).'<br>';
        // }
        // $data1 = [];
        // foreach(array_unique($data) as $v){
        //     $arr = [];
        //     foreach($listTimezone as $l) {
            
        //         $from = Carbon::now()->tz($l)->endOfMonth()->setTimezone('UTC');
        //         if($v == $from) {
        //             $arr[] = $l;
        //         }
        //     }
        //     $data1[$v] = $arr;
        // }

        $listTimezone = json_decode(config('myconfig.constants.all_timezone'));
        foreach($listTimezone as $t) {
            echo($t[0].'<br>');
            HandleAffiliateTierMonthly::dispatch($t);
        }
        // dd(json_encode($data1));
        // 
    }
    
    public function uploadFileExcel()
    {
        return view('upload_file_excel');
    }

    public function importAffiliateTranslation(Request $request)
    {
        $data=$this->categoryService->uploadFileExcel($request->file('file'));
        $users = (new FastExcel)->configureCsv(',')->import($data['pathExcel'], function ($line) {
        $checkExist = Translate::where('locale','en')->where('key',$line['Key'])->first();
        if(!$checkExist)
        {
            Translate::create([
                'locale' => 'en',
                'key' => $line['Key'],
                'text' => $line['Text_content']
           ]) ;
        }
           $arrLanguage = ['es','zh','de','fr','pt','it'] ;
           foreach($arrLanguage as $language)
           {
            $from = 'en';
            dispatch(new ImportAffiliateTranslation($line['Text_content'],$line['Key'],$language,$from))->onQueue('import')->delay(Carbon::now()->addSeconds(1));
           }
        });
        Storage::delete($data['path']);
    }
}
