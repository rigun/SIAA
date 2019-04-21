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
            $table->string('code_sparepart');
            $table->unsignedBigInteger('id_pemesanan');
            $table->foreign('code_sparepart')->references('code')->on('spareparts');
            $table->foreign('id_pemesanan')->references('id')->on('pemesanans');
          
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
