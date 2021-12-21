<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVarantIdIndexInProductCommissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_commissions', function (Blueprint $table) {
            $table->dropUnique(['variant_id']);
            // $table->index(["variant_id"], 'variant_id');
            $table->unique(array('group_id', 'variant_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_commission', function (Blueprint $table) {
            //
        });
    }
}
