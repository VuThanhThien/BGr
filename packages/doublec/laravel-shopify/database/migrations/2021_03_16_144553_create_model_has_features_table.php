<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelHasFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_has_features', function (Blueprint $table) {
            $table->string('feature_slug')->index();
            $table->integer('model_id')->index();
            $table->string('model_type');
            $table->float('limit')->default(0);
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
        Schema::dropIfExists('model_has_features');
    }
}
