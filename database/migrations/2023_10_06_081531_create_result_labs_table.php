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
        Schema::create('result_labs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs');
            $table->string('description')->nullable();
            $table->string('hasil_lab')->nullable();
            $table->enum('status', ['proses', 'success'])->default('proses');
            $table->foreignId('user_id')->constrainded('users');
            $table->foreignId('kunjungan_id')->constrained('kunjungans')->onDelete('cascade');
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
        Schema::dropIfExists('result_labs');
    }
};
