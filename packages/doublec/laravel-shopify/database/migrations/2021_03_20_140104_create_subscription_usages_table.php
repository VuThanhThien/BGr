<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_usages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('subscription_id')->index();
            $table->string('feature_slug')->index();
            $table->float('used')->default(0);
            $table->dateTime('expires_at');
            $table->unique(['subscription_id', 'feature_slug']);
            $table->foreign('subscription_id')->references('id')->on('subscription_histories')->onDelete('cascade');
            $table->foreign('feature_slug')->references('slug')->on('features')->onDelete('cascade');
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
        Schema::dropIfExists('subscription_usages');
    }
}
