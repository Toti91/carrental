<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car', function (Blueprint $table){
            $table->increments('id');
            $table->integer('category_id');
            $table->string('name');
            $table->integer('price');
            $table->integer('gamma');
            $table->string('status');
            $table->string('image');
            $table->integer('popularity');
            $table->integer('maint_cost');
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
        Schema::drop('car');
    }
}
