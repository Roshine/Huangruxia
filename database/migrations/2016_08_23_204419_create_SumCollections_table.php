<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSumCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SumCollections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('weekId');
            $table->integer('groupId');
            $table->string('stuId');
            $table->string('stuName');
            $table->text('summary');
            $table->string('marked')->default('no');
            $table->integer('score')->nullable();
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
        //
    }
}
