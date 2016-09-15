<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeekScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekScore', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stuId');
            $table->integer('week');
            $table->float('preScore')->nullable();
            $table->float('homeworkScore')->nullable();
            $table->float('sumScore')->nullable();
            $table->float('weekScore')->default(0);
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
        Schema::drop('weekScore');
    }
}
