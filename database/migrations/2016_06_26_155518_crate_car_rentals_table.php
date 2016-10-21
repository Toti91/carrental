<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateCarRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_rentals', function(blueprint $table){
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name');
            $table->integer('money');
            $table->string('icon');
            $table->integer('tutorial');
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
        Schema::drop('car_rentals');
    }
}
