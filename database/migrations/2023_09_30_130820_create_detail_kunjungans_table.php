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
        Schema::create('detail_kunjungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans')->onDelete('cascade');
            $table->foreignId('user_id')->nullable();
            $table->foreignId('poli_id')->nullable();
            $table->foreignId('room_id')->nullable();
            $table->text('diagnosa')->nullable();
            $table->text('resep')->nullable();
            $table->foreignId('apotek_id')->nullable();
            $table->bigInteger('pembayaran')->nullable();
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
        Schema::dropIfExists('detail_kunjungans');
    }
};
