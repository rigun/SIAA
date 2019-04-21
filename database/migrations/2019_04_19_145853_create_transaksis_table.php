<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->string('nomor_transaksi');
            $table->string('namaKonsumen');
            $table->string('alamatKonsumen');
            $table->string('nomorKonsumen');
            $table->string('idCS');
            $table->foreign('idCS')->references('id')->on('pegawais');
            $table->string('idKasir');
            $table->foreign('idKasir')->references('id')->on('pegawais');
            $table->date('tanggalTransaksi');
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
        Schema::dropIfExists('transaksis');
    }
}
