<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id');
            $table->unsignedInteger('shop_id');
            $table->unsignedInteger('affiliate_id');
            $table->bigInteger('order_id')->nullable();
            $table->string('order_name')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->bigInteger('refund_id')->nullable();
            $table->text('customer_info')->nullable();
            $table->text('order_info')->nullable();
            $table->tinyInteger('commission_type')->comment('1:percent_of_sale, 2: flat_rate_per_order, 3:flat_rate_per_item');
            $table->decimal('commission_amount', 10,2);
            $table->integer('quantity');
            $table->decimal('total', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('commission', 10, 2);
            $table->tinyInteger('status')->default(1)->comment('1:approved, 2:pending, 3:paid, 4:rejected');
            $table->tinyInteger('tracking_type')->comment('1:link,2:coupon');
            $table->integer('payment_id')->nullable();
            $table->timestamps();
            $table->index(["order_id"], 'order_id');
            $table->index(["refund_id"], 'refund_id');
            $table->index(["group_id"], 'group_id');
            $table->index(["payment_id"], 'payment_id');
            $table->foreign('shop_id')
                ->references('id')->on('shops')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('affiliate_id')
                ->references('id')->on('affiliates')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversions');
    }
}
