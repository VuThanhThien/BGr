<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailOutboxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_outbox', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id')->index();
            $table->string('from');
            $table->string('to');
            $table->string('subject', 500);
            $table->string('status',30);
            $table->string('message_id')->unique();
            $table->text('error_message')->nullable();
            $table->unsignedInteger('affiliate_id')->nullable();
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('set null');
            $table->string('email_type',50)->nullable();
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
        Schema::dropIfExists('mail_outbox');
    }
}
