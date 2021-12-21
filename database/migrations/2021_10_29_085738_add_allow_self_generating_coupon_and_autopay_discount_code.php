<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllowSelfGeneratingCouponAndAutopayDiscountCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_settings', function (Blueprint $table) {
            $table->boolean('allow_self_generating_coupon')->default(0);
            $table->string('autopay_discount_code', 500)->nullable();
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
