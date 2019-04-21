<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSparepartKendaraansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sparepart_kendaraans', function (Blueprint $table) {
            $table->string('kode_sparepart');
            $table->unsignedBigInteger('id_kendaraan');
            $table->foreign('kode_sparepart')->references('kode')->on('spareparts');
            $table->foreign('id_kendaraan')->references('id')->on('motors');
            $table->primary(['kode_sparepart','id_kendaraan']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sparepart_kendaraans');
    }
}
