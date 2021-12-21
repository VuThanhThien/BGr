<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_template', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');
            $table->string('name');
            $table->text('subject');
            $table->longText('content');
            $table->tinyInteger('status');
            $table->string('slug');
            $table->index(["shop_id"], 'shop_id');
            $table->unique(array('shop_id', 'slug'));
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
        Schema::dropIfExists('email_template');
    }
}
