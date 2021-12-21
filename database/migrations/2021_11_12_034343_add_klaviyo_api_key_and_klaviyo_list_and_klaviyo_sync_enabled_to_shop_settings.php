<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKlaviyoApiKeyAndKlaviyoListAndKlaviyoSyncEnabledToShopSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_settings', function (Blueprint $table) {
            $table->text('klaviyo')->nullable();
            $table->boolean('klaviyo_sync_enabled')->default(0);
            $table->boolean('sync_klaviyo_approved_affiliate')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_settings', function (Blueprint $table) {
            //
        });
    }
}
