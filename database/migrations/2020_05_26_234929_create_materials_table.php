<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('acquisition_id');
            $table->foreign('acquisition_id')->references('id')->on('acquisitions');
            $table->string('name');
            $table->string('slug');
            $table->enum('unity_type', [
                \App\Material::UNITY,
                \App\Material::PACKAGE,
                \App\Material::REAM,
                \App\Material::SET,
                \App\Material::BOX
            ]);
            $table->bigInteger('stock');
            $table->string('picture')->nullable();
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
        Schema::dropIfExists('materials');
    }
}
