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
            $table->bigInteger('num_order');
            $table->unsignedInteger('dispatcher_id');
            $table->foreign('dispatcher_id')->references('id')->on('dispatchers');
            $table->unsignedInteger('office_id');
            $table->foreign('office_id')->references('id')->on('offices');
            $table->enum('status', [
                \App\Order::ENABLED,
                \App\Order::PROCESING,
                \App\Order::FINISHED
            ]);
            $table->string('name_responsible');
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
