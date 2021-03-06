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
            $table->bigIncrements('id');
            $table->string('nomor_transaksi');
            $table->unsignedBigInteger('id_cabang');
            $table->foreign('id_cabang')->references('id')->on('cabangs');
            $table->string('namaKonsumen');
            $table->string('alamatKonsumen');
            $table->string('nomorKonsumen');
            $table->unsignedBigInteger('idCS');
            $table->foreign('idCS')->references('id')->on('pegawais');
            $table->unsignedBigInteger('idKasir')->nullable();
            $table->foreign('idKasir')->references('id')->on('pegawais');
            $table->double('totalServices')->default(0);
            $table->double('totalSpareparts')->default(0);
            $table->double('totalCost')->default(0);
            $table->double('payment')->default(0);
            $table->double('diskon')->default(0);
            $table->integer('status')->default(0);
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
