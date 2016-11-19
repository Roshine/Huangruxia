<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChapterSumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ChapterSum', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stuId');
            $table->integer('chapter');
            $table->integer('chapterSumScore')->nullable();
            $table->float('chapterScore')->nullable();
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
        Schema::drop('ChapterSum');
    }
}
