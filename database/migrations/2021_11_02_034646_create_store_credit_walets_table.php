<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreCreditWaletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_credit_walets', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('total',10,2)->default(0);
            $table->unsignedInteger('affiliate_id')->unique();
            $table->unsignedInteger('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade')->onUpdate('cascade');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('store_credit_walets');
    }
}
