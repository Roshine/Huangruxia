<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Assessments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('weekId');
            $table->integer('groupId');
            $table->string('stuId');
            $table->string('stuName');
            $table->string('peerId');
            $table->string('peerName');
            $table->integer('assessment');
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
        Schema::drop('Assessments');
    }
}
