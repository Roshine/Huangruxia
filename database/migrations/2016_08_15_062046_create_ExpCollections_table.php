<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ExpCollections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stuId');
            $table->integer('expTempId');
            $table->string('result')->nullable();
            $table->integer('resScore')->nullable();
            $table->text('experience')->nullable();
            $table->integer('expScore')->nullable();
            $table->string('marked')->default('no');
            $table->string('difficulty')->nullable();
            $table->integer('expReportScore')->nullable();
            $table->string('addReportScore')->default('no');
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
        Schema::drop('ExpCollections');
    }
}
