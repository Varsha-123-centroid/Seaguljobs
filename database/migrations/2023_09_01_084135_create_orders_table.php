<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->integer('star');
            $table->integer('pooja');
            $table->string('date', 50);
            $table->string('amount',100);
            $table->integer('temple_id');
            $table->integer('addresses_id');
            $table->string('order_no',100);
            $table->integer('status')->comment(' 0 for pending,1 for done,2 for cancelled');
            $table->integer('created_by');
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
        Schema::dropIfExists('orders');
    }
};
