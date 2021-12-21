<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id')->index();
            $table->bigInteger('charge_id')->index();
            $table->string('name');
            $table->decimal('price', 8, 2)->unsigned();
            $table->integer('trial_days')->unsigned();
            $table->enum('type', ['recurring', 'one-time'])->index();
            $table->timestamp('billing_on')->nullable();
            $table->timestamp('trial_ends_on')->nullable();
            $table->timestamp('cancelled_on')->nullable();
            $table->boolean('test')->index();
            $table->string('status')->index();
            $table->text('description');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charges');
    }
}
