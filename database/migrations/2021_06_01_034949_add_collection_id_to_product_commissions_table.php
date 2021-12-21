<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollectionIdToProductCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_commissions', function (Blueprint $table) {
            $table->unsignedInteger('collection_id')->after('shop_id')->nullable();
            $table->foreign('collection_id')
                ->references('id')->on('collection_commission')
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
        Schema::table('product_commissions', function (Blueprint $table) {
            //
        });
    }
}
