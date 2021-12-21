<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id');
            $table->string('logo')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('sub_domain', 50)->unique();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('from_contact_name')->nullable();
            $table->string('from_contact_email')->nullable();
            $table->text('favicon')->nullable();
            $table->tinyInteger('aff_conflict_resolution')->default(2)->comment('1:first, 2:last');
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
        Schema::dropIfExists('shop_settings');
    }
}
