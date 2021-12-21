<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultAffiliateLanguageAndEnableAffiliateLanguageAndAutoDetectLanguageToShopSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_settings', function (Blueprint $table) {
            $table->string('default_affiliate_language',10)->comment('en:English, es:Spanish, zh:Chinese, de:German,fr:French,pt:Portuguese,it:Italian')->default('en');
            $table->boolean('enable_affiliate_language')->default(1);
            $table->boolean('auto_detect_language')->default(0);
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
