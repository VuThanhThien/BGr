<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plan_id')->nullable();
            $table->string('name', 255);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->enum('type', ["percentage", "amount"]);
            $table->float('value')->unsigned()->nullable();
            $table->integer('usage_limit')->unsigned()->nullable();
            $table->integer('trial_days')->default(0);
            $table->timestamps();
            $table->foreign('plan_id')
                ->references('id')
                ->on('plans')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
