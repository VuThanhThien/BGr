<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateTierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_tier', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id')->unique();
            $table->integer('level_number');
            $table->string('data_levels', 500)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('level_condition');
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
        Schema::dropIfExists('affiliate_tier');
    }
}
