<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stuId');
            $table->string('name');
            $table->integer('privilege')->default(0);
            $table->string('email')->nullable();
            $table->string('password');
            $table->char('gender')->nullable();
            $table->integer('class');
            $table->integer('groupId');
            $table->integer('styleId')->nullable();
            $table->string('nickname')->nullable();
            $table->float('expExam')->nullable();
            $table->float('finalScore')->nullable();
            $table->rememberToken();
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
        Schema::drop('students');
    }
}
