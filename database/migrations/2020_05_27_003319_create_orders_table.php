<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('dispatcher_id');
            $table->foreign('dispatcher_id')->references('id')->on('dispatchers');
            $table->enum('status', [
                \App\Order::ENABLED,
                \App\Order::PROCESING,
                \App\Order::FINISHED,
                \App\Order::CANCELED
            ])->default(\App\Order::ENABLED);
            $table->string('name_responsible')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
