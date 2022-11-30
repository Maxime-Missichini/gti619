<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });

        Schema::create('abilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });

        Schema::create('ability_role', function (Blueprint $table) {
            $table->primary(['role_id','ability_id']);
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('ability_id');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('Cascade');

            $table->foreign('ability_id')
                ->references('id')
                ->on('abilities')
                ->onDelete('Cascade');
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->primary(['user_id','role_id']);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('Cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('Cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            Schema::dropIfExists('roles');
            Schema::dropIfExists('abilities');
            Schema::dropIfExists('role_user');
            Schema::dropIfExists('ability_role');
        });
    }
}
