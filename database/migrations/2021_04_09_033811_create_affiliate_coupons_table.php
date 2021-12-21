<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->index();
            $table->text('description')->nullable();
            $table->unsignedInteger('affiliate_id');
            $table->unsignedInteger('shop_id');
            $table->foreign('affiliate_id')
                ->references('id')->on('affiliates')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('shop_id')
                ->references('id')->on('shops')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('coupons');
    }
}
