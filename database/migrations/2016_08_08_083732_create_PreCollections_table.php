<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PreCollections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stuId');
            $table->integer('preTempId');
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
        Schema::drop('PreCollections');
    }
}
