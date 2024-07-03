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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->integer('temple_id');
            $table->integer('deity_id');
            $table->integer('pooja');
            $table->string('unit_price',100);
            $table->string('pooja_start', 50);
            $table->string('pooja_end', 50);
            $table->string('days', 50);
            $table->string('qty_per_day', 50);
            $table->string('total_price',100);
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
        Schema::dropIfExists('wishlists');
    }
};
