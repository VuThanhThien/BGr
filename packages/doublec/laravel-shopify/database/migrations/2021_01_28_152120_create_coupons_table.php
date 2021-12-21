<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 128)->index();
            $table->string('shop', 255)->nullable()->index();
            $table->unsignedInteger('discount_id')->index();
            $table->integer('times_used')->unsigned()->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->foreign('discount_id')
                ->references('id')
                ->on('discounts')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
