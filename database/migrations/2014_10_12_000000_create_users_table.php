<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        //  roles
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        //  users
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id')->default(\App\Role::DISPATCHER);
            $table->foreign('role_id')->references('id')->on('roles');
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('slug');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
        Shema::dropIfExists('roles');
    }
}
