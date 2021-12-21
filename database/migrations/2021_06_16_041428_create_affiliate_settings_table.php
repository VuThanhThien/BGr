<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('affiliate_id');
            $table->string('payment_method')->nullable();
            $table->text('payment_info')->nullable();
            $table->index('payment_method');
            $table->foreign('affiliate_id')
                ->references('id')->on('affiliates')
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
        Schema::dropIfExists('affiliate_settings');
    }
}
