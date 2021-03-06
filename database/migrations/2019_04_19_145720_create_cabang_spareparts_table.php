<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCabangSparepartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cabang_spareparts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_sparepart');
            $table->unsignedBigInteger('id_cabang');
            $table->foreign('id_cabang')->references('id')->on('cabangs');
            $table->foreign('kode_sparepart')->references('kode')->on('spareparts');
            $table->double('hargaBeli');
            $table->double('hargaJual');
            $table->string('tempat');
            $table->integer('stok');
            $table->integer('limitStok');
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
        Schema::dropIfExists('cabang_spareparts');
    }
}
