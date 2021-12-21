<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id');
            $table->string('email');
            $table->string('password');
            $table->integer('group_id');
            $table->boolean('is_active')->default(0);
            $table->string('first_name')->nullable()->default(null);
            $table->string('last_name')->nullable()->default(null);
            $table->string('company')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->string('city')->nullable()->default(null);
            $table->string('zipcode')->nullable()->default(null);
            $table->string('state')->nullable()->default(null);
            $table->string('country')->nullable()->default(null);
            $table->string('phone')->nullable()->default(null);
            $table->string('facebook', 1000)->nullable()->default(null);
            $table->string('youtube', 1000)->nullable()->default(null);
            $table->string('instagram', 1000)->nullable()->default(null);
            $table->string('twitter', 1000)->nullable()->default(null);
            $table->string('website', 1000)->nullable()->default(null);
            $table->boolean('is_pending')->default(1);
            $table->text('personal_detail')->nullable()->default(null);
            $table->string('hash_code')->unique();
            $table->integer('parent_id')->default(0);
            $table->timestamps();
            $table->index(["parent_id"], 'parent_id');
            $table->index(["group_id"], 'group_id');
            $table->foreign('shop_id')
                ->references('id')->on('shops')
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
        Schema::dropIfExists('affiliates');
    }
}
