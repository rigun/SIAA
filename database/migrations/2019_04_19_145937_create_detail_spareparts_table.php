<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailSparepartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_spareparts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_detailtransaksi');
            $table->foreign('id_detailtransaksi')->references('id')->on('detail_transaksis');
            $table->string('kode_sparepart');
            $table->foreign('kode_sparepart')->references('kode')->on('spareparts');
            $table->integer('jumlah');
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
        Schema::dropIfExists('detail_spareparts');
    }
}
