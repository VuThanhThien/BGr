<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('shop_id');
            $table->unsignedInteger('affiliate_id');
            $table->string('client_id');
            $table->string('cart_token')->nullable()->default(null);
            $table->string('checkout_token')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('expire_at')->nullable()->default(null);
            $table->index(["client_id"], 'client_id');
            $table->index(["cart_token"], 'cart_token');
            $table->index(["checkout_token"], 'checkout_token');
            $table->foreign('shop_id')
                ->references('id')->on('shops')
                ->onDelete('cascade')
                ->onUpdate('cascade');
//            $table->foreignId('shop_id')
//                ->constrained()
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
            $table->foreign('affiliate_id')
                ->references('id')->on('affiliates')
                ->onDelete('cascade')
                ->onUpdate('cascade');
//            $table->foreignId('affiliate_id')
//                ->constrained()
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracking');
    }
}
