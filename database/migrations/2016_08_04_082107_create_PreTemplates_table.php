<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PreTemplates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('target');
            $table->text('content');        //存放题目和选项
            $table->string('answers');      //正确答案
            $table->string('everyAnsNum')->default('');  //每个答案选的的人数
            $table->integer('week');
            $table->string('published')->default('no');
            $table->date('startTime');
            $table->date('deadLine');
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
        Schema::drop('PreTemplates');
    }
}
