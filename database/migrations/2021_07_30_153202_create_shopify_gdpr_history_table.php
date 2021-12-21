<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyGdprHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_gdpr_history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 30);
            $table->bigInteger('shop_id');
            $table->string('shop');
            $table->text('data');
            $table->index(["shop_id"], 'shop_id');
            $table->index(["shop"], 'shop');
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
        Schema::dropIfExists('shopify_gdpr_history');
    }
}
