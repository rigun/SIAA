<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPemesanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pemesanans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_pemesanan');
            $table->foreign('id_pemesanan')->references('id')->on('pemesanans');
            $table->string('kode_sparepart');
            $table->foreign('kode_sparepart')
                  ->references('kode')->on('spareparts')
                  ->onDelete('cascade');
            $table->string('satuan');
            $table->integer('total');
            $table->double('hargaBeli');
            $table->integer('totalDiterima')->default(0);
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
        Schema::dropIfExists('detail_pemesanans');
    }
}
