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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 50);
            $table->string('dob', 50);
            $table->string('gender', 50);
            $table->integer('star');
            $table->string('id_card_type', 50);
            $table->string('id_card_no', 100);
            $table->string('id_card', 500)->nullable();
            $table->integer('is_admin')->default(0)->comment('1-admin');
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
        Schema::dropIfExists('members');
    }
};
