<?php

namespace App\Console\Commands;

use App\Models\ShopSetting;
use Illuminate\Console\Command;

class updateLogoShopSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:updateLogoShopSettings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update logo shopsettings to path';

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
        $shopSettings = ShopSetting::get();
        foreach($shopSettings as $shopSetting)
        {
            if($shopSetting->path_name)
            {
                $shopSetting->update(['logo'=> $shopSetting->path_name]);
            }
        }
    }
}
