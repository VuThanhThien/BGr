<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreCreditCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_credit_coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->index();
            $table->string('description')->nullable();
            $table->unsignedInteger('affiliate_id');
            $table->unsignedInteger('shop_id');
            $table->tinyInteger('discount_type')->comment('1:percent_of_sale, 2: flat_rate, 3:free_shipping');
            $table->decimal('discount_amount',10,2);
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_credit_coupons');
    }
}
