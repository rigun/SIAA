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
            $table->unsignedBigInteger('id_sparepart_cabang');
            $table->foreign('id_sparepart_cabang')->references('id')->on('cabang_spareparts');
            $table->unsignedBigInteger('id_pemesanan');
            $table->foreign('id_pemesanan')->references('id')->on('pemesanans');
            $table->integer('total');
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
