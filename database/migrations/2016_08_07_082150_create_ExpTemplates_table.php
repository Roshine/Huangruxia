<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ExpTemplates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('target');
            $table->text('content');
            $table->string('answers');
            $table->string('everyAnsNum')->default('');
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
        Schema::drop('ExpTemplates');
    }
}
