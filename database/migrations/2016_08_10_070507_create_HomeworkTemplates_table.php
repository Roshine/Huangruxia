<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeworkTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('HomeworkTemplates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('target');
            $table->text('content');
            $table->string('answers')->default('auto');
            $table->string('everyAnsNum')->default('');
            $table->string('published')->default('no');
            $table->integer('week');
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
        Schema::drop('HomeworkTemplates');
    }
}
