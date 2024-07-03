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
        Schema::create('poojas', function (Blueprint $table) {
            $table->id();
            $table->string('pooja_name',500);
            $table->string('description',1000);
            $table->string('price',100);
            $table->string('schedule',100);
            $table->json('date',500)->nullable();
            $table->string('image',1000)->nullable();
            $table->integer('temple_id');
            $table->integer('deity_id');
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
        Schema::dropIfExists('poojas');
    }
};
