<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDoneStartedAndIsSkipStartedToShopSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_settings', function (Blueprint $table) {
            $table->boolean('is_skip_started')->default(0);
            $table->boolean('is_done_started')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_settings', function (Blueprint $table) {
            //
        });
    }
}
