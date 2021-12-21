<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('variant_id')->unique();
            $table->bigInteger('product_id');
            $table->string('product_title');
            $table->string('variant_title');
            $table->tinyInteger('commission_type')->comment('1:percent, 2: flat_rate');
            $table->decimal('commission_amount', 10, 2);
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('shop_id');
            $table->index(["variant_id"], 'variant_id');
            $table->index(["product_id"], 'product_id');
            $table->foreign('group_id')
                ->references('id')->on('groups')
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
        Schema::dropIfExists('product_commissions');
    }
}
