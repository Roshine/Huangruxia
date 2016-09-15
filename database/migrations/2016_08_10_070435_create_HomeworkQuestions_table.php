<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeworkQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('HomeworkQuestions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');      //题目和选项
            $table->string('answer');
            $table->integer('chapter')->nullable();
            $table->integer('week');
            $table->integer('submitNum')->default(0);
            $table->integer('correctNum')->default(0);
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
        Schema::drop('HomeworkQuestions');
    }
}
