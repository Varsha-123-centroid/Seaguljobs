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
        Schema::create('darshans', function (Blueprint $table) {
            $table->id();
            $table->integer('temple_id');
            $table->string('date', 50);
            $table->string('time', 50);
            $table->string('no_person', 50);
            $table->json('name')->nullable();
            $table->json('age')->nullable();
            $table->json('gender')->nullable();
            $table->json('id_type')->nullable();
            $table->json('idcard_no')->nullable();
            $table->string('uniq_code', 100);
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
        Schema::dropIfExists('darshans');
    }
};
