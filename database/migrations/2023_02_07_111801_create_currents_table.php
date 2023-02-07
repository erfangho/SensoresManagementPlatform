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
        Schema::create('currents', function (Blueprint $table) {
            $table->id();
            $table->integer('value');
            $table->unsignedBigInteger('device_id');
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->text('detail')->nullable();
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
        Schema::dropIfExists('currents');
    }
};
