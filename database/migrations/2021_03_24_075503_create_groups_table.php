<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_private')->default(0);
            $table->boolean('is_default')->default(0);
            $table->tinyInteger('commission_type')->comment('1:percent_of_sale, 2: flat_rate_per_order, 3:flat_rate_per_item');
            $table->decimal('commission_amount', 10,2);
            $table->tinyInteger('customer_commission_type')->default(0)->comment('0:no reward, 1:percent, 2: flat');
            $table->decimal('customer_commission_amount', 10,2)->nullable();
            $table->integer('cookie_days')->default(30);
            $table->tinyInteger('multiple_orders_a_customer_type')->default(1)->comment('1:cookie, 2:first time, 3:lifetime');
            $table->boolean('auto_approve_order')->default(0);
            $table->boolean('auto_approve_affiliate')->default(0);
            $table->integer('holding_period')->default(0);
            $table->decimal('minimum_payable_amount')->default(0);
            $table->boolean('exclude_shipping')->default(1);
            $table->boolean('exclude_tax')->default(1);
            $table->boolean('include_eu_vat')->default(0);
            $table->boolean('exclude_vat')->default(0);
            $table->boolean('include_discounts')->default(0);
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
        Schema::dropIfExists('groups');
    }
}
