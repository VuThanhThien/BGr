<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConversionIdAndChildAffiliateToConversionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conversions', function (Blueprint $table) {
            $table->tinyInteger('level')->default(0);
            $table->unsignedInteger('conversion_id')->nullable();
            $table->unsignedInteger('child_affiliate_id')->nullable();
            $table->index(["child_affiliate_id"], 'child_affiliate_id');
            $table->index(["conversion_id"], 'conversion_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversions', function (Blueprint $table) {
            //
        });
    }
}
