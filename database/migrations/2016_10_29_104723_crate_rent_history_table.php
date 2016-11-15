<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateRentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rent_history', function (Blueprint $table){
            $table->increments('id');
            $table->integer('rent_id');
            $table->integer('total_rent');
            $table->integer('time');
            $table->string('start');
            $table->string('end');
            $table->boolean('malfunction');
            $table->boolean('trigger_maintenance');
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
        Schema::drop('rent_history');
    }
}
