<?php

namespace App\Console\Commands;

use App\Models\Affiliate;
use App\Models\ShopSetting;
use Illuminate\Console\Command;

class setAffiliatePrimaryLinkNull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'affiliate:setAffiliatePrimary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $shopSettings = ShopSetting::with('info:shop_id,domain')->get();
        foreach($shopSettings as $shopSetting)
        {
            ShopSetting::where('shop_id',$shopSetting->shop_id)->update(['default_affiliate_link'=>'https://'.$shopSetting->info->domain]);
        }
        Affiliate::whereNotNull('primary_aff_link')->update(['primary_aff_link'=>null]);
    }
}
