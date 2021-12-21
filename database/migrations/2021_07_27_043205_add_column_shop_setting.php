<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnShopSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_settings', function(Blueprint $table) {
            $table->boolean('auto_generate_coupon')->default(0);
            $table->boolean('allow_changing_discount_code')->default(0);
            $table->boolean('allow_specifying_discount_code')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
