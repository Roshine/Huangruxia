<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeworkCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('HomeworkCollections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stuId');
            $table->integer('HomeworkTempId');
            $table->string('questionId')->default('');
            $table->string('answers')->default('');
            $table->string('result');
            $table->integer('resScore');
            $table->text('experience');
            $table->integer('expScore')->nullable();
            $table->string('marked')->default('no');
            $table->string('difficulty');
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
        Schema::drop('HomeworkCollections');
    }
}
