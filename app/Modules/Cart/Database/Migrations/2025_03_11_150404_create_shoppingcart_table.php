<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingcartTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(config('cart.database.table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identifier');
            $table->string('instance');
            $table->json('content');
            $table->double('tax',9,3)->nullable();
            $table->double('discount',9,3)->nullable();
            $table->double('subtotal',9,3)->nullable();
            $table->double('total',9,3)->nullable();

            $table->nullableTimestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop(config('cart.database.table'));
    }
}
