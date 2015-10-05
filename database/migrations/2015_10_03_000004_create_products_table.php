<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->string('name', 200);
            $table->text('description')
            $table->double('mimimum_price', 10, 2);
            $table->date('start_sale');
            $table->date('start_end');
        });

        Schema::table('products', function (Blueprint $table) {
          //$table->foreign('user_id')->references('id')->on('users');
          //$table->foreign('category_id')->references('id')->on('categories');
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
