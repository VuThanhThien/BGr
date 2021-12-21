<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCheckoutPopupData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_settings', function (Blueprint $table) {
            $table->longText('checkout_popup_data')->nullable();
            $table->boolean('activate_popup_checkout')->default(0);
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
