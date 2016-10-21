<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_user', function (Blueprint $table){
            $table->increments('id');
            $table->integer('car_id');
            $table->integer('user_id');
            $table->string('plate');
            $table->integer('km_count');
            $table->integer('price');
            $table->integer('start');
            $table->integer('end');
            $table->integer('maint_count');
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
        Schema::drop('car_user');
    }
}
